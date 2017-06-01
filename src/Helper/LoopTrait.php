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
 *
 * @package  Cradle
 * @category Helper
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait LoopTrait
{
    /**
     * Loops through returned result sets
     *
     * @param *callable $callback the callback method
     * @param int       $i        the incrementor
     *
     * @return LoopTrait
     */
    public function loop(callable $callback, int $i = 0)
    {
        $bound = $callback;
        if ($callback instanceof Closure) {
            $bound = $callback->bindTo($this, get_class($this));
        }

        if (call_user_func($bound, $i) !== false) {
            $this->loop($callback, $i + 1);
        }

        return $this;
    }
}
