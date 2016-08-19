<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Event;

use Exception;

/**
 * Event exceptions
 *
 * @package  Cradle
 * @category Event
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class EventException extends Exception
{
    /**
     * @const string ERROR_INVALID_CALLBACK Error template
     */
    const ERROR_INVALID_CALLBACK = 'Invalid callback passed.';
    
    /**
     * Create a new exception for invalid callback
     *
     * @return EventException
     */
    public static function forInvalidCallback()
    {
        return new static(static::ERROR_INVALID_CALLBACK);
    }
}
