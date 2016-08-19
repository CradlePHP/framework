<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Http\Middleware;

/**
 * Express style middleware object
 *
 * @vendor   Cradle
 * @package  Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
interface MiddlewareInterface
{
    /**
     * Adds global middleware
     *
     * @param function $callback The middleware handler
     *
     * @return MiddlewareInterface
     */
    public function register($callback);

    /**
     * Process middleware
     *
     * @return bool
     */
    public function process(...$args);
}
