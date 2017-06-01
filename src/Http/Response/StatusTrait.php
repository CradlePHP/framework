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
 * Designed for the Response Object; Adds methods to process status codes
 *
 * @vendor   Cradle
 * @package  Server
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait StatusTrait
{
    /**
     * Returns the status code
     *
     * @return int|null
     */
    public function getStatus()
    {
        return $this->get('code');
    }

    /**
     * Sets a status code
     *
     * @param *int    $code   Status code
     * @param *string $status The string literal code for header
     *
     * @return StatusTrait
     */
    public function setStatus(int $code, string $status)
    {
        return $this
            ->set('code', $code)
            ->setHeader('Status', $status);
    }
}
