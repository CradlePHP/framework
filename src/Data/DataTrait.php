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
 * The DataTrait combines all the data features
 * in the Data package. Just a shortcut for having
 * all the features in one go.
 *
 * @package  Cradle
 * @category Data
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait DataTrait
{
    use ArrayAccessTrait, IteratorTrait, CountableTrait, DotTrait, MagicTrait, GeneratorTrait;

    protected $data = [];
}
