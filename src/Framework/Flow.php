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
use Cradle\Resolver\ResolverException;

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
        try {
            //NOTE: Resolvers should provide a cache
            return self::getResolver()->resolve($name, ...$args);
        } catch (ResolverException $e) {
            throw Exception::forFlowNotFound($name);
        }
    }

    /**
     * Pattern to solve:
     * Cannot bind an instance to a static closure
     * for PHP 5.6, a PHP Quirk :)
     */
    public function __construct()
    {
        $self = $this;
        self::register('reset', function () use ($self) {
            return $self->reset();
        });

        self::register('forward', function () use ($self) {
            return $self->forward();
        });
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
        self::register('session', function () {
            static $instance;

            if (!$instance) {
                $instance = new Session();
            }

            return $instance;
        });

        self::register('log', function () {
            static $instance;

            if (!$instance) {
                $instance = new Log();
            }

            return $instance;
        });

        new static();
    }

    /**
     * Brings the stage to results
     *
     * @return Closure
     */
    private function forward()
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
    private function reset()
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
