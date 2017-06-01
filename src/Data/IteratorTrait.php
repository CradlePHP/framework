<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Data;

/**
 * Given that there's $data this will
 * auto setup the Iterator interface
 *
 * @package  Cradle
 * @category Data
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait IteratorTrait
{
    /**
     * Returns the current item
     * For Iterator interface
     */
    public function current()
    {
        return current($this->data);
    }

    /**
     * Returns th current position
     * For Iterator interface
     */
    public function key()
    {
        return key($this->data);
    }

    /**
     * Increases the position
     * For Iterator interface
     */
    public function next()
    {
        next($this->data);
    }

    /**
     * Rewinds the position
     * For Iterator interface
     */
    public function rewind()
    {
        reset($this->data);
    }

    /**
     * Validates whether if the index is set
     * For Iterator interface
     *
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->data[$this->key()]);
    }
}
