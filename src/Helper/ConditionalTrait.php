<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Helper;

use Closure;

/**
 * Adds a generic `when()` method used during chainable calls
 *
 * @package  Cradle
 * @category Helper
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait ConditionalTrait
{
    /**
     * Invokes Callback if conditional callback is true
     *
     * @param *callable|scalar|null $conditional should evaluate to true
     * @param *callable             $success     called when conditional is true
     * @param callable              $fail        called when conditional is false
     *
     * @return mixed
     */
    public function when($conditional, callable $success, callable $fail = null)
    {
        //bind conditional if it's not bound
        if ($conditional instanceof Closure) {
            $conditional = $conditional->bindTo($this, get_class($this));
        }

        //bind success if it's not bound
        if ($success instanceof Closure) {
            $success = $success->bindTo($this, get_class($this));
        }

        //bind fail if it's not bound
        if ($fail instanceof Closure) {
            $fail = $fail->bindTo($this, get_class($this));
        }

        //default results is null
        $results = null;

        //if condition is true
        if ((is_callable($conditional) && call_user_func($conditional))
            || (!is_callable($conditional) && $conditional)
        ) {
            //call success
            $results = call_user_func($success);
        } else if (is_callable($fail)) {
            //call fail
            $results = call_user_func($fail);
        }

        //do we have results ?
        if ($results !== null) {
            //then return it
            return $results;
        }

        //otherwise return this
        return $this;
    }
}
