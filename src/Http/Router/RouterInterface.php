<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Http\Router;

use Cradle\Http\Request\RequestInterface;

/**
 * Handles method-path matching and routing
 *
 * @vendor   Cradle
 * @package  Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
interface RouterInterface
{

    /**
     * Process routes
     *
     * @return bool
     */
    public function process(RequestInterface $request, ...$args): bool;

    /**
     * Adds routing middleware
     *
     * @param string   $method   The request method
     * @param string   $pattern  The route pattern
     * @param callable $callback The middleware handler
     *
     * @return RouterInterface
     */
    public function route(string $method, string $pattern, callable $callback): RouterInterface;
}
