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
     * @param function $callback The middleware handler
     *
     * @return Middleware
     */
    public function register($callback)
    {
        $this->registry[] = $callback;
        return $this;
    }

    /**
     * Process middleware
     *
     * @return bool
     */
    public function process(...$args)
    {
        foreach ($this->registry as $callback) {
            if (call_user_func_array($callback, $args) === false) {
                return false;
            }
        }
        
        return true;
    }
}
