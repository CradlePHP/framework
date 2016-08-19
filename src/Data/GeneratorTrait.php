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
 * Allows $data to be iterable using generators
 *
 * @package  Cradle
 * @category Data
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait GeneratorTrait
{
    /**
     * Loop generator
     */
    public function generator()
    {
        foreach ($this->data as $key => $value) {
            yield $key => $value;
        }
    }
}
