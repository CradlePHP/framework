<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\I18n;

use Exception;

/**
 * Language exceptions
 *
 * @package  Cradle
 * @category I18n
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class LanguageException extends Exception
{

    /**
     * @const string ERROR_INVALID_CALLBACK Error template
     */
    const ERROR_FILE_NOT_SET = 'No file was specified';

    /**
     * Create a new exception for file not set
     *
     * @return LanguageException
     */
    public static function forFileNotSet(): LanguageException
    {
        return new static(static::ERROR_FILE_NOT_SET);
    }
}
