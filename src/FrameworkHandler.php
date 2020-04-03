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
use Cradle\Http\Request;
use Cradle\Http\Response;

use Cradle\Event\EventHandler;
use Cradle\Event\PipeTrait;

/**
 * Handler for micro framework calls. Combines both
 * Http handling and Event handling
 *
 * @vendor   Cradle
 * @package  Framework
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class FrameworkHandler
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
     * @param array $handlers
     */
    protected $handlers = [];

    /**
     * Custom Invoker for package calling
     *
     * @param *string $package name of package
     *
     * @return Package
     */
    public function __invoke(string $package): Package
    {
        return $this->package($package);
    }

    /**
     * Adds routing middleware for all methods
     *
     * @param *string          $path     The route path
     * @param *callable|string $callback The middleware handler
     * @param callable|string  ...$args  Arguments for flow
     *
     * @return FrameworkHandler
     */
    public function all(string $path, $callback, ...$args): FrameworkHandler
    {
        return $this->route('all', $path, $callback, ...$args);
    }

    /**
     * Adds routing middleware for DELETE method
     *
     * @param *string          $path     The route path
     * @param *callable|string $callback The middleware handler
     * @param callable|string  ...$args  Arguments for flow
     *
     * @return FrameworkHandler
     */
    public function delete(string $path, $callback, ...$args): FrameworkHandler
    {
        return $this->route('delete', $path, $callback, ...$args);
    }

    /**
     * Adds routing middleware for GET method
     *
     * @param *string          $path     The route path
     * @param *callable|string $callback The middleware handler
     * @param callable|string  ...$args  Arguments for flow
     *
     * @return FrameworkHandler
     */
    public function get(string $path, $callback, ...$args): FrameworkHandler
    {
        return $this->route('get', $path, $callback, ...$args);
    }

    /**
     * Returns all the packages
     *
     * @param string|null $name Name of package
     *
     * @return array
     */
    public function getPackages(string $name = null): array
    {
        if (isset($this->packages[$name])) {
            return $this->packages[$name];
        }

        return $this->packages;
    }

    /**
     * Returns all the protocols
     *
     * @return array
     */
    public function getProtocols(): array
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
    public function export(string $event, bool $map = false)
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
            if ($meta === EventHandler::STATUS_OK) {
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

        return [$request, $response, $next];
    }

    /**
     * Imports a set of flows
     *
     * @param *array $flows
     *
     * @return FrameworkHandler
     */
    public function import(array $flows): FrameworkHandler
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
     * Creates a new Request and Response
     *
     * @param bool $load whether to load the RnRs
     *
     * @return array
     */
    public function makePayload($load = true)
    {
        $request = Request::i();
        $response = Response::i();

        if ($load) {
            $request->load();
            $response->load();

            $stage = $this->getRequest()->getStage();

            if (is_array($stage)) {
                $request->setSoftStage($stage);
            }
        }

        return [
            'request' => $request,
            'response' => $response
        ];
    }

    /**
     * Runs an event like a method
     *
     * @param bool $load whether to load the RnRs
     *
     * @return array
     */
    public function method($event, $request = [], Response $response = null)
    {
        if (is_array($request)) {
            $request = Request::i()->load()->set('stage', [])->setStage($request);
        }

        if (!($request instanceof Request)) {
            $request = Request::i()->load();
        }

        if (is_null($response)) {
            $response = Response::i()->load();
        }

        $this->trigger($event, $request, $response);

        if ($response->isError()) {
            return false;
        }

        return $response->getResults();
    }

    /**
     * Adds routing middleware for POST method
     *
     * @param *string          $path     The route path
     * @param *callable|string $callback The middleware handler
     * @param callable|string  ...$args  Arguments for flow
     *
     * @return FrameworkHandler
     */
    public function post(string $path, $callback, ...$args): FrameworkHandler
    {
        return $this->route('post', $path, $callback, ...$args);
    }

    /**
     * Adds routing middleware for PUT method
     *
     * @param *string          $path     The route path
     * @param *callable|string $callback The middleware handler
     * @param callable|string  ...$args  Arguments for flow
     *
     * @return FrameworkHandler
     */
    public function put(string $path, $callback, ...$args): FrameworkHandler
    {
        return $this->route('put', $path, $callback, ...$args);
    }

    /**
     * Registers and initializes a package
     *
     * @param *string|callable $vendor The vendor/package name
     *
     * @return FrameworkHandler
     */
    public function register($vendor): FrameworkHandler
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
     * @param *string          $method   The request method
     * @param *string          $path     The route path
     * @param *callable|string $callback The middleware handler
     * @param callable|string  ...$args  Arguments for flow
     *
     * @return FrameworkHandler
     */
    public function route(string $method, string $path, $callback, ...$args): FrameworkHandler
    {
        array_unshift($args, $callback);

        foreach ($args as $i => $callback) {
            $priority = 0;
            if (isset($args[$i + 1]) && is_numeric($args[$i + 1])) {
                $priority = $args[$i + 1];
            }

            //if it's a string
            if (is_string($callback)) {
                //it's an event
                $event = $callback;
                //make into callback
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
                $this->routeHttp($method, $path, $callback, $priority);
            }
        }

        return $this;
    }

    /**
     * Sets up a sub handler given the path.
     *
     * @param *string               $root The root path to handle
     * @param FrameworkHandler|null $handler the child handler
     *
     * @return FrameworkHandler
     */
    public function handler(string $root, FrameworkHandler $handler = null): FrameworkHandler
    {
        //if we have this handler in memory
        if (isset($this->handlers[$root])) {
            //if a handler was provided
            if ($handler instanceof FrameworkHandler) {
                //we mean to change up the handler
                $this->handlers[$root] = $handler;
            }

            //either way return the handler
            return $this->handlers[$root];
        }

        //otherwise the handler is not in memory
        if (!($handler instanceof FrameworkHandler)) {
            // By default
            //  - Routes are unique per Handler.
            //  - Middleware are unique per Handler.
            //  - Child pre processors aren't triggered.
            //  - Child post processors aren't triggered.
            //  - Child error processors are set by Parent.
            //  - Child protocols are set by Parent.
            //  - Child packages are set by Parent.
            //  - Child requests are set by Parent.
            //  - Child responses are set by Parent.
            //  - Events are still global.
            $handler = FrameworkHandler::i()->setParent($this);
        }

        //remember the handler
        $this->handlers[$root] = $handler;

        //since this is out first time with this,
        //lets have the parent listen to the root and all possible
        $this->all($root . '**', function ($request, $response) use ($root) {
            //we need the original path
            $path = $request->getPath('string');
            //determine the sub route
            $route = substr($path, strlen($root));

            //because substr('/', 1); --> false
            if (!is_string($route) || !strlen($route)) {
                $route = '/';
            }

            //set up the sub rout in request
            $request->setPath($route);

            //we want to lazy load this in because it is
            //possible that the hander could have changed
            $this->handler($root)
                ->setRequest($request)
                ->setResponse($response)
                ->process();

            //bring the path back
            $request->setPath($path);
        });

        return $this->handlers[$root];
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
     * @param FrameworkHandler $parent
     *
     * @return FrameworkHandler
     */
    public function setParent(FrameworkHandler $parent): FrameworkHandler
    {
        //use the parent protocols
        $this->protocols = $parent->getProtocols();
        //use the parent packages
        $this->packages = $parent->getPackages();
        //use the parent event handler
        $this->setEventHandler($parent->getEventHandler());
        //use the parent error processor
        $this->setErrorProcessor($parent->getErrorProcessor());

        return $this;
    }
}
