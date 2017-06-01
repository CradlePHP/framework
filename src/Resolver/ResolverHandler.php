<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Resolver;

/**
 * A resolver is a container interface used to manage class
 * dependancies which is useful to properly test classes
 *
 * @package  Cradle
 * @category Resolver
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class ResolverHandler implements ResolverInterface
{

    /**
     * @var array $registry A list of resolutions
     */
    protected $registry = [];

    /**
     * @var array $share A list of shared resolutions
     */
    protected static $share = [];

    /**
     * Returns true if name can be resolved
     *
     * @param *string $name
     *
     * @return bool
     */
    public function canResolve(string $name): bool
    {
        return $this->isRegistered($name) || class_exists($name) || is_callable($name);
    }

    /**
     * Returns true if name is registered
     *
     * @param *string $name
     *
     * @return bool
     */
    public function isRegistered(string $name): bool
    {
        return isset($this->registry[$name]);
    }

    /**
     * Returns true if name is shared
     *
     * @param *string $name
     *
     * @return bool
     */
    public function isShared(string $name): bool
    {
        return isset(self::$share[$name]);
    }

    /**
     * Registers a resolver callback
     *
     * @param *string   $name     Name of Resolver
     * @param *callable $callback What to execute when we need resolving
     *
     * @return ResolverInterface
     */
    public function register(string $name, callable $callback): ResolverInterface
    {
        //if it's not callable
        if (!is_callable($callback)) {
            throw ResolverException::forInvalidCallback();
        }

        $this->registry[$name] = $callback;
        return $this;
    }

    /**
     * Does the resolving
     *
     * @param *string $name Name of Resolver
     * @param *mixed  $args What to execute when we need resolving
     *
     * @return mixed
     */
    public function resolve(string $name, ...$args)
    {
        //if we can't resolve it
        if (!$this->canResolve($name)) {
            throw ResolverException::forResolverNotFound($name);
        }

        //is it registered ?
        if ($this->isRegistered($name)) {
            $callback = $this->registry[$name];
            return call_user_func_array($callback, $args);
        }

        //if class exist
        if (class_exists($name)) {
            //it is a class, let's register this
            $this->register($name, function (...$args) use ($name) {
                //if the static method i exists
                if (method_exists($name, 'i')) {
                    //instantiate it
                    return forward_static_call_array([$name, 'i'], $args);
                }

                return new $name(...$args);
            });

            //then resolve this
            return $this->resolve($name, ...$args);
        }

        //its callable, let's register this
        $this->register($name, function (...$args) use ($name) {
            return call_user_func_array($name, $args);
        });

        //then resolve this
        return $this->resolve($name, ...$args);
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
    public function resolveStatic(string $name, string $method, ...$args)
    {
        return $this->resolve($name . '::' . $method, ...$args);
    }

    /**
     * Does the resolving but considers shared
     *
     * @param *string $name Name of Resolver
     * @param *mixed  $args What to execute when we need resolving
     *
     * @return mixed
     */
    public function shared(string $name, ...$args)
    {
        if (!isset(self::$share[$name])) {
            self::$share[$name] = $this->resolve($name, ...$args);
        }

        return self::$share[$name];
    }
}
