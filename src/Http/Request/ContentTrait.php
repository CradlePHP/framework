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
 * Designed for the Request Object; Adds methods to store raw input
 *
 * @vendor   Cradle
 * @package  Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait ContentTrait
{
    /**
     * Returns final input stream
     *
     * @return string|null
     */
    public function getContent()
    {
        return $this->get('body');
    }

    /**
     * Returns true if has content
     *
     * @return bool
     */
    public function hasContent(): bool
    {
        return !$this->isEmpty('body');
    }

    /**
     * Sets content
     *
     * @param *mixed $content
     *
     * @return ContentTrait
     */
    public function setContent($content)
    {
        $this->set('body', $content);
        return $this;
    }
}
