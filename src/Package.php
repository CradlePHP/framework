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

/**
 * Package space for package methods
 *
 * @vendor   Cradle
 * @package  Framework
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Package
{
    /**
     * @const NO_METHOD Error template
     */
    const NO_METHOD = 'No method named %s was found';
    
    /**
     * @var array $methods A list of virtual methods
     */
    protected $methods = array();

    /**
     * When a method doesn't exist, it this will try to call one
     * of the virtual methods.
     *
     * @param *string $name name of method
     * @param *array  $args arguments to pass
     *
     * @return mixed
     */
    public function __call($name, $args)
    {
        if (isset($this->methods[$name])) {
            return call_user_func_array($this->methods[$name], $args);
        }

        throw Exception::forMethodNotFound($name);
    }

    /**
     * Registers a method to be used
     *
     * @param *string  $name     The class route name
     * @param *Closure $callback The callback handler
     *
     * @return Package
     */
    public function addMethod($name, Closure $callback)
    {
        $this->methods[$name] = $callback->bindTo($this, get_class($this));
        
        return $this;
    }
}
