<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Http;

use Cradle\Http\Middleware\MiddlewareInterface;

/**
 * Express style middleware object
 *
 * @vendor   Cradle
 * @package  Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Middleware implements MiddlewareInterface
{
    /**
     * @const string UNEXPECTED_GLOBAL Error template
     */
    const UNEXPECTED_GLOBAL = 'Unexpected end before routing. Please check global middlewares.';

    /**
     * @var array $registry A list of middleware callbacks
     */
    protected $registry = [];

    /**
     * Adds global middleware
     *
     * @param callable $callback The middleware handler
     *
     * @return MiddlewareInterface
     */
    public function register(callable $callback): MiddlewareInterface
    {
        $this->registry[] = $callback;
        return $this;
    }

    /**
     * Process middleware
     *
     * @param mixed ...$args
     *
     * @return bool
     */
    public function process(...$args): bool
    {
        foreach ($this->registry as $callback) {
            if (call_user_func_array($callback, $args) === false) {
                return false;
            }
        }

        return true;
    }
}
