<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Framework;

use Closure;
use Cradle\Helper\InstanceTrait;
use Cradle\Helper\LoopTrait;
use Cradle\Helper\ConditionalTrait;

use Cradle\Profiler\InspectorTrait;
use Cradle\Profiler\LoggerTrait;

use Cradle\Resolver\StateTrait;
use Cradle\Resolver\ResolverException;

use Cradle\Http\HttpTrait;
use Cradle\Event\PipeTrait;

/**
 * Handler for micro framework calls. Combines both
 * Http handling and Event handling using event pipes.
 *
 * @vendor   Cradle
 * @package  Framework
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class App
{
    use HttpTrait,
        InstanceTrait,
        LoopTrait,
        ConditionalTrait,
        InspectorTrait,
        LoggerTrait,
        StateTrait,
        PipeTrait,
        PackageTrait
        {
            HttpTrait::route as routeHttp;
            HttpTrait::all as allHttp;
            HttpTrait::delete as deleteHttp;
            HttpTrait::get as getHttp;
            HttpTrait::post as postHttp;
            HttpTrait::put as putHttp;
            PackageTrait::register as registerPackage;
            PackageTrait::__constructPackage as __construct;
        }
    
    /**
     * Attempts to use __callData then __callResolver
     *
     * @param *string $name name of method
     * @param *array  $args arguments to pass
     *
     * @return mixed
     */
    public function __call($name, $args)
    {
        try {
            return $this->__callResolver($name, $args);
        } catch (ResolverException $e) {
            throw new Exception($e->getMessage());
        }
    }
    
    /**
     * Custom Invoker for package calling
     *
     * @param *string $package name of package
     *
     * @return Package
     */
    public function __invoke($package)
    {
        return $this->package($package);
    }

    /**
     * Adds routing middleware for all methods
     *
     * @param *string  $path     The route path
     * @param *string  $callback The middleware handler
     * @param string   ...$args  Arguments for flow
     *
     * @return App
     */
    public function all($path, $callback, ...$args)
    {
        return $this->route('all', $path, $callback, ...$args);
    }
    
    /**
     * Sets up a sub handler given the path.
     * Notes when setting this:
     *  - Routes are unique per Handler.
     *  - Middleware are unique per Handler.
     *  - Child pre processors aren't triggered.
     *  - Child post processors aren't triggered.
     *  - Child error processors are set by Parent.
     *  - Child protocols are set by Parent.
     *  - Child packages are set by Parent.
     *  - Child requests are set by Parent.
     *  - Child responses are set by Parent.
     *  - Events are still global.
     *
     * @param string $root The root path to handle
     * @param the child handler
     *
     * @return App
     */
    public function app($root, App $handler)
    {
        $this->route('all', $root . '**', function($request, $response) use ($root, $handler) {
            //we need the original path
            $path = $request->getPath('string');
            $request->setPath(substr($path, strlen($root)));

            $handler
                ->setParent($this)
                ->setRequest($request)
                ->setResponse($response)
                ->process();

            //bring the path back
            $request->setPath($path);
        });
        
        return $this;
    }
    
    /**
     * Adds routing middleware for DELETE method
     *
     * @param *string  $path     The route path
     * @param *string  $callback The middleware handler
     * @param string   ...$args  Arguments for flow
     *
     * @return App
     */
    public function delete($path, $callback, ...$args)
    {
        return $this->route('delete', $path, $callback, ...$args);
    }
    
    /**
     * Adds routing middleware for GET method
     *
     * @param *string  $path     The route path
     * @param *string  $callback The middleware handler
     * @param string   ...$args  Arguments for flow
     *
     * @return App
     */
    public function get($path, $callback, ...$args)
    {
        return $this->route('get', $path, $callback, ...$args);
    }
    
    /**
     * Returns all the packages
     *
     * @return array
     */
    public function getPackages()
    {
        return $this->packages;
    }
    
    /**
     * Returns all the protocols
     *
     * @return array
     */
    public function getProtocols()
    {
        return $this->protocols;
    }
    
    /**
     * Exports a flow to another external interface
     *
     * @param *string $event
     * @param bool    $map
     *
     * @return Closure|array
     */
    public function export($event, $map = false)
    {
        $handler = $this;
        
        $next = function (...$args) use ($handler, $event, $map) {
            $request = $handler->getRequest();
            $response = $handler->getResponse();

            $meta = $handler
                //do this directly from the handler
                ->getEventHandler()
                //trigger
                ->trigger($event, $request, $response, ...$args)
                //if our events returns false
                //lets tell the interface the same
                ->getMeta();
            
            //no map ? let's try our best
            //if we have meta
            if ($meta) {
                //return the response
                return $response->getContent(true);
            }
            
            //otherwise return false
            return false;
        };
        
        if (!$map) {
            return $next;
        }
    
        $request = $handler->getRequest();
        $response = $handler->getResponse();
    
        return array($request, $response, $next);
    }
    
    /**
     * Imports a set of flows
     *
     * @param *array $flows
     *
     * @return App
     */
    public function import(array $flows)
    {
        foreach ($flows as $flow) {
            //it's gotta be an array
            if (!is_array($flow)) {
                continue;
            }
            
            $this->flow(...$flow);
        }
        
        return $this;
    }
    
    /**
     * Adds routing middleware for POST method
     *
     * @param *string  $path     The route path
     * @param *string  $callback The middleware handler
     * @param string   ...$args  Arguments for flow
     *
     * @return App
     */
    public function post($path, $callback, ...$args)
    {
        return $this->route('post', $path, $callback, ...$args);
    }
    
    /**
     * Adds routing middleware for PUT method
     *
     * @param *string  $path     The route path
     * @param *string  $callback The middleware handler
     * @param string   ...$args  Arguments for flow
     *
     * @return App
     */
    public function put($path, $callback, ...$args)
    {
        return $this->route('put', $path, $callback, ...$args);
    }
    
    /**
     * Registers and initializes a package
     *
     * @param *string $vendor The vendor/package name
     *
     * @return App
     */
    public function register($vendor)
    {
        //if it's callable
        if (is_callable($vendor)) {
            //it's not a package
            //it's a preprocess
            return $this->preprocess($vendor);
        }

        return $this->registerPackage($vendor);
    }

    /**
     * Adds routing middleware
     *
     * @param *string $method The request method
     * @param *string $path   The route path
     * @param *mixed ...$args The middleware handler
     *
     * @return App
     */
    public function route($method, $path, $callback, ...$args)
    {   
        //if no args
        if (empty($args)) {
            //if it's a string
            if (is_string($callback)) {
                //it's an event
                $event = $callback;
                $callback = function ($request, $response) use ($event) {
                    $this->trigger($event, $request, $response);
                };    
            }
            
            //if it's closure
            if ($callback instanceof Closure) {
                //bind it
                $callback = $this->bindCallback($callback);
            }
            
            //if it's callable
            if (is_callable($callback)) {
                //route it
                $this->routeHttp($method, $path, $callback);
            }
            
            return $this;
        }

        //we are going to make a flow
        $event = $method . ' ' . $path;
        
        //which is now an event driven route
        $this->flow($event, $callback, ...$args);

        $callback = function ($request, $response) use ($event) {
            $this->trigger($event, $request, $response);
        };
        
        $this->routeHttp($method, $path, $this->bindCallback($callback));

        return $this;
    }

    /**
     * Setting this means that this is a child
     * handler. Notes when setting this:
     *  - Routes are unique per Handler.
     *  - Middleware are unique per Handler.
     *  - Child pre processors aren't triggered.
     *  - Child post processors aren't triggered.
     *  - Child error processors are set by Parent.
     *  - Child protocols are set by Parent.
     *  - Child packages are set by Parent.
     *  - Child requests are set by Parent.
     *  - Child responses are set by Parent.
     *  - Events are still global.
     *
     * @param App $parent
     *
     * @return App
     */
    public function setParent(App $parent)
    {
        //use the parent error processor
        $this->errorProcessor = $parent->getErrorProcessor();
        //use the parent protocols
        $this->protocols = $parent->getProtocols();
        //use the parent packages
        $this->packages = $parent->getPackages();
        
        return $this;
    }
}
