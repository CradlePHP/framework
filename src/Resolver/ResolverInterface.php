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
interface ResolverInterface
{
    /**
     * Returns true if name can be resolved
     *
     * @param *string $name
     *
     * @return bool
     */
    public function canResolve(string $name): bool;

    /**
     * Returns true if name is registered
     *
     * @param *string $name
     *
     * @return bool
     */
    public function isRegistered(string $name): bool;

    /**
     * Returns true if name is shared
     *
     * @param *string $name
     *
     * @return bool
     */
    public function isShared(string $name): bool;

    /**
     * Registers a resolver callback
     *
     * @param *string   $name     Name of Resolver
     * @param *callable $callback What to execute when we need resolving
     *
     * @return ResolverInterface
     */
    public function register(string $name, callable $callback): ResolverInterface;

    /**
     * Does the resolving
     *
     * @param *string $name Name of Resolver
     * @param *mixed  $args What to execute when we need resolving
     *
     * @return mixed
     */
    public function resolve(string $name, ...$args);

    /**
     * Does the resolving but considers shared
     *
     * @param *string $name Name of Resolver
     * @param *mixed  $args What to execute when we need resolving
     *
     * @return mixed
     */
    public function shared(string $name, ...$args);
}
