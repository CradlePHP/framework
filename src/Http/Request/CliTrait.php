<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Http\Request;

/**
 * Designed for the Request Object; Adds CLI methods
 *
 * @vendor   Cradle
 * @package  Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait CliTrait
{
    /**
     * Returns CLI args if any
     *
     * @return array|null
     */
    public function getArgs()
    {
        return $this->get('args');
    }

    /**
     * Sets CLI args
     *
     * @param *array|null
     *
     * @return CliTrait
     */
    public function setArgs(array $argv = null)
    {
        return $this->set('args', $argv);
    }
}
