<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Resolver;

use Closure;

/**
 * This will add resolver features to any
 * class that wants to be test friendly
 *
 * @package  Cradle
 * @category Resolver
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait ResolverTrait
{
    /**
     * @var Resolver|null $globalResolverHandler The resolver instance
     */
    protected static $globalResolverHandler = null;
    
    /**
     * @var Resolver|null $routeResolver The resolver instance
     */
    protected $resolverHandler = null;
    
    /**
     * This is so you can tack on to the __call method
     *
     * @param *string $name name of method
     * @param *array  $args arguments to pass
     *
     * @return mixed
     */
    public function __callResolver($name, $args)
    {
        //get the handler
        $handler = $this->getResolverHandler();
        
        //can it be resolved?
        if ($handler->canResolve($name)) {
            //is it shared ?
            if ($handler->isShared($name)) {
                //resolve the shared
                return $this->resolveShared($name, ...$args);
            }
            
            //resolve it the regular way
            return $this->resolve($name, ...$args);
        }
        
        //oops ?
        throw ResolverException::forMethodNotFound(get_class($this), $name);
    }
    
    /**
     * Adds a resolver callback
     *
     * @return ResolverTrait
     */
    public function addResolver($name, $callback)
    {
        //if it's not callable
        if (!is_callable($callback)) {
            throw ResolverException::forInvalidCallback();
        }
        
        //if it's a closure, they meant to bind the callback
        if ($callback instanceof Closure) {
            //so there's no scope
            $callback = $callback->bindTo($this, get_class($this));
        }
        
        $this->getResolverHandler()->register($name, $callback);
        
        return $this;
    }

    /**
     * Returns the resolver handler
     *
     * @return ResolverHandler
     */
    public function getResolverHandler()
    {
        if (is_null(self::$globalResolverHandler)) {
            //this should be the only place where
            //a hard coded dependancy exists
            self::$globalResolverHandler = new ResolverHandler();
        }
        
        if (is_null($this->resolverHandler)) {
            $this->resolverHandler = self::$globalResolverHandler;
        }
        
        return $this->resolverHandler;
    }

    /**
     * Does the resolving
     *
     * @param *string $name Name of Resolver
     * @param *mixed  $args What to execute when we need resolving
     *
     * @return mixed
     */
    public function resolve($name, ...$args)
    {
        return $this->getResolverHandler()->resolve($name, ...$args);
    }

    /**
     * Resolves shared
     *
     * @param *string $name Name of Resolver
     * @param *mixed  $args What to execute when we need resolving
     *
     * @return mixed
     */
    public function resolveShared($name, ...$args)
    {
        return $this->getResolverHandler()->shared($name, ...$args);
    }

    /**
     * Resolves static methods
     *
     * @param *string $name Name of class
     * @param *string $name Name of method
     * @param *mixed  $args What to execute when we need resolving
     *
     * @return mixed
     */
    public function resolveStatic($name, $method, ...$args)
    {
        return $this->getResolverHandler()->resolveStatic($name, $method, ...$args);
    }
    
    /**
     * Sets the resolver handler
     *
     * @return ResolverTrait
     */
    public function setResolverHandler(ResolverInterface $handler, $static = false)
    {
        if ($static) {
            self::$globalResolverHandler = $handler;
        }
        
        $this->resolverHandler = $handler;
        
        return $this;
    }
}
