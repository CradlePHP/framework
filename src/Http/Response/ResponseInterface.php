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
 * Http Response Object
 *
 * @vendor   Cradle
 * @package  Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
interface ResponseInterface
{
    /**
     * Adds a header parameter
     *
     * @param *string     $name  Name of the header
     * @param string|null $value Value of the header
     *
     * @return ResponseInterface
     */
    public function addHeader($name, $value = null);
    
    /**
     * Returns the content body
     *
     * @return mixed
     */
    public function getContent();
    
    /**
     * Returns either the header value given
     * the name or the all headers
     *
     * @return mixed
     */
    public function getHeaders($name = null);
    
    /**
     * Returns the status code
     *
     * @return int
     */
    public function getStatus();
    
    /**
     * Returns true if content is set
     *
     * @return bool
     */
    public function hasContent();

    /**
     * Removes a header parameter
     *
     * @param string $name Name of the header
     *
     * @return ResponseInterface
     */
    public function removeHeader($name);
    
    /**
     * Sets the content
     *
     * @param *mixed $content Can it be an array or string please?
     *
     * @return ResponseInterface
     */
    public function setContent($content);
    
    /**
     * Sets a status code
     *
     * @param *int    $code   Status code
     * @param *string $status The string literal code for header
     *
     * @return ResponseInterface
     */
    public function setStatus($code, $status);
}
