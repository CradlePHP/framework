<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Http\Response;

/**
 * Designed for the Response Object; Adds methods to process raw content
 *
 * @vendor   Cradle
 * @package  Server
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait ContentTrait
{
    /**
     * Returns the content body
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->get('body');
    }

    /**
     * Returns true if content is set
     *
     * @return bool
     */
    public function hasContent(): bool
    {
        $body = $this->get('body');
        return !is_null($body) && strlen((string) $body);
    }

    /**
     * Sets the content
     *
     * @param *mixed $content Can it be an array or string please?
     *
     * @return ResponseInterface
     */
    public function setContent($content)
    {
        if (!is_scalar($content)) {
            $content = json_encode($content, JSON_PRETTY_PRINT);
        }

        if (is_bool($content)) {
            $content = $content ? '1': '0';
        }

        if (is_null($content)) {
            $content = '';
        }

        return $this->set('body', $content);
    }
}
