<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Framework;

use Exception as PHPException;

/**
 * Framework exceptions
 *
 * @package  Cradle
 * @category Framework
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Exception extends PHPException
{
    /**
     * @const ERROR_FLOW_NOT_FOUND Error template
     */
    const ERROR_FLOW_NOT_FOUND = 'Flow::%s() is not defined';

    /**
     * @const ERROR_METHOD_NOT_FOUND Error template
     */
    const ERROR_METHOD_NOT_FOUND = 'No method named %s was found';

    /**
     * @const string ERROR_PACKAGE_NOT_FOUND Error template
     */
    const ERROR_PACKAGE_NOT_FOUND = 'Could not find package: %s';

    /**
     * Create a new exception for invalid method
     *
     * @param *string $name
     *
     * @return Exception
     */
    public static function forFlowNotFound(string $name): Exception
    {
        return new static(sprintf(static::ERROR_FLOW_NOT_FOUND, $name));
    }

    /**
     * Create a new exception for invalid method
     *
     * @param *string $name
     *
     * @return Exception
     */
    public static function forMethodNotFound(string $name): Exception
    {
        return new static(sprintf(static::ERROR_METHOD_NOT_FOUND, $name));
    }

    /**
     * Create a new exception for invalid package
     *
     * @param *string $vendor
     *
     * @return Exception
     */
    public static function forPackageNotFound(string $vendor): Exception
    {
        return new static(sprintf(static::ERROR_PACKAGE_NOT_FOUND, $vendor));
    }
}
