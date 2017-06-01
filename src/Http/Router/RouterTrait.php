<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Http\Router;

use Cradle\Http\Router;

/**
 * Designed for the HttpHandler we are parting this out
 * to lessen the confusion
 *
 * @package  Cradle
 * @category Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait RouterTrait
{
    /**
     * @var Router|null $router Response object to use
     */
    protected $router = null;

    /**
     * Adds routing middleware for all methods
     *
     * @param *string   $path     The route path
     * @param *callable $callback The middleware handler
     *
     * @return RouterTrait
     */
    public function all(string $path, callable $callback)
    {
        return $this->route('all', $path, $callback);
    }

    /**
     * Adds routing middleware for delete method
     *
     * @param *string   $path     The route path
     * @param *callable $callback The middleware handler
     *
     * @return RouterTrait
     */
    public function delete(string $path, callable $callback)
    {
        return $this->route('delete', $path, $callback);
    }

    /**
     * Adds routing middleware for get method
     *
     * @param *string   $path     The route path
     * @param *callable $callback The middleware handler
     *
     * @return RouterTrait
     */
    public function get(string $path, callable $callback)
    {
        return $this->route('get', $path, $callback);
    }

    /**
     * Returns a router object
     *
     * @return RouterTrait
     */
    public function getRouter()
    {
        if (is_null($this->router)) {
            if (method_exists($this, 'resolve')) {
                $this->setRouter($this->resolve(Router::class));
            } else {
                $this->setRouter(new Router());
            }
        }

        return $this->router;
    }

    /**
     * Adds routing middleware for post method
     *
     * @param *string   $path     The route path
     * @param *callable $callback The middleware handler
     *
     * @return RouterTrait
     */
    public function post(string $path, callable $callback)
    {
        return $this->route('post', $path, $callback);
    }

    /**
     * Adds routing middleware for put method
     *
     * @param *string   $path     The route path
     * @param *callable $callback The middleware handler
     *
     * @return RouterTrait
     */
    public function put(string $path, callable $callback)
    {
        return $this->route('put', $path, $callback);
    }

    /**
     * Adds routing middleware
     *
     * @param *string   $method   The request method
     * @param *string   $path     The route path
     * @param *callable $callback The middleware handler
     *
     * @return RouterTrait
     */
    public function route(string $method, string $path, callable $callback)
    {
        $this->getRouter()->route($method, $path, $callback);

        return $this;
    }

    /**
     * Sets the router to use
     *
     * @param *RouterInterface $router
     *
     * @return RouterTrait
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;

        return $this;
    }

    /**
     * Manually trigger a route
     *
     * @param *string $method
     * @param *string $path
     * @param mixed   $args
     *
     * @return RouterTrait
     */
    public function triggerRoute(string $method, string $path, ...$args)
    {
        $event = strtoupper($method).' '.$path;

        $this
            ->getRouter()
            ->getEventHandler()
            ->trigger($event, ...$args);

        return $this;
    }
}
