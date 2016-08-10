<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Framework;

use Cradle\Framework\Flow\Log;
use Cradle\Framework\Flow\File;
use Cradle\Framework\Flow\Session;
use Cradle\Resolver\ResolverHandler;

/**
 * A Flow is a facade that delays actual 
 * methods from being called immediately
 * or otherwise a delayed controller class
 *
 * From
 * `Auth->doSomething()`
 *
 * To
 * `Flow::auth()->dosomething()`
 *
 * Except that there should be a real 
 * class and a facade class separately
 *
 * @vendor   Cradle
 * @package  Framework
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Flow
{
    /**
     * @var ResolverHandler $resolver
     */
    protected static $resolver = null;

    /**
     * @var object $controller
     */
    protected $controller = null;
    
    /**
     * Sets up the action
     *
     * @param *Model  $model
     * @param *string $action
     */
    public function __construct($controller)
    {    
        $this->controller = $controller;
    }
    
    /**
     * Delays an action step call for
     * example `Flow::auth()->task()`
     *
     * @param *string $name
     * @param *array  $args
     *
     * @return array
     */
    public function __call($name, $args)
    {
        $controller = $this->controller;
        return function($request, $response) use ($controller, $name, &$args) {
            //we should throw a method exist error at runtime
            $results = $controller->$name(...$args);
            
            if ($results instanceof $controller) {
                return;
            }
            
            return $results;
        };
    }
    
    /**
     * Sets up the flow for
     * example `Flow::auth()`
     *
     * @param *string $name
     * @param *array  $args
     *
     * @return Flow
     */
    public static function __callStatic($name, $args)
    {
        //NOTE: Resolvers should provide a cache   
        return self::getResolver()->shared($name, ...$args);
    }
    
    /**
     * Returns the action property
     * example `Flow::auth()->yes`
	 *         `Flow::auth()->search->load`
     *
     * @param *string $name
     *
     * @return string
     */
    public function __get($name)
    {
        return $this->controller->$name;
    }
    
    /**
     * Tries to get a resolver
     * if not it makes one
     *
     * @return ResolverHandler
     */
    public static function getResolver()
    {
        if (is_null(self::$resolver)) {
            self::setResolver(new ResolverHandler());
        }
        
        return self::$resolver;
    }
    
    /**
     * Registers a Controller
     *
     * @param *string $name
     * @param *callable $callback
     */
    public static function register($name, $callback)
    {
        self::getResolver()->register($name, $callback);
    }
    
    /**
     * You can overwrite the resolver with this
     *
     * @param *ResolverHandler $resolver
     */
    public static function setResolver(ResolverHandler $resolver)
    {
        self::$resolver = $resolver;
        
        //self add some default flows
        self::register('session', function() {
			static $instance;
			
			if(!$instance) {
				$instance = new Session();
			}
			
            return $instance;
        });
        
        self::register('file', function() {
            static $instance;
			
			if(!$instance) {
				$instance = new File();
			}
			
            return $instance;
        });
        
        self::register('log', function() {
            static $instance;
			
			if(!$instance) {
				$instance = new Log();
			}
			
            return $instance;
        });
        
        self::register('reset', function() {
            return self::reset();
        });
        
        self::register('forward', function() {
            return self::forward();
        });
    }

    /**
     * Brings the stage to results
     *
     * @return Closure
     */
    private static function forward()
    {
        return function ($request, $response) {
            $stage = $request->getStage();
            
            if (empty($stage)) {
                return;
            }
            
            foreach ($stage as $key => $value) {
                $response->setResults($key, $value);
            }
        };
    }

    /**
     * Brings the results back to staging
     *
     * @return Closure
     */
    private static function reset()
    {
        return function ($request, $response) {
            $results = $response->getResults();
            
            if (empty($results)) {
                return;
            }
            
            foreach ($results as $key => $value) {
                //it's quite impossible for a POST or 
                //GET to be a number for example
                if (is_numeric($key)) {
                    continue;
                }

                $request->setStage($key, $value);
            }
        };
    }
}
