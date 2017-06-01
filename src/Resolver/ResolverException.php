<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Resolver;

use Exception;

/**
 * Resolver exceptions
 *
 * @package  Cradle
 * @category Resolver
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class ResolverException extends Exception
{
    /**
     * @const string ERROR_CLASS_NOT_FOUND Error template
     */
    const ERROR_CLASS_NOT_FOUND = 'Could not find class %s.';

    /**
     * @const string ERROR_METHOD_NOT_FOUND Error template
     */
    const ERROR_METHOD_NOT_FOUND = 'Could not find method %s->%s().';

    /**
     * @const string ERROR_METHOD_NOT_FOUND Error template
     */
    const ERROR_INVALID_RESOLVER = 'Could not find resolver %s.';

    /**
     * Create a new exception for missing class
     *
     * @param *string $class
     *
     * @return ResolverException
     */
    public static function forClassNotFound(string $class): ResolverException
    {
        $message = sprintf(static::ERROR_CLASS_NOT_FOUND, $class);
        return new static($message);
    }

    /**
     * Create a new exception for missing method
     *
     * @param *string $class
     * @param *string $method
     *
     * @return ResolverException
     */
    public static function forMethodNotFound($class, $method)
    {
        $message = sprintf(static::ERROR_METHOD_NOT_FOUND, $class, $method);
        return new static($message);
    }

    /**
     * Create a new exception for missing resolver
     *
     * @param *string $name
     *
     * @return ResolverException
     */
    public static function forResolverNotFound($name)
    {
        $message = sprintf(static::ERROR_INVALID_RESOLVER, $name);
        return new static($message);
    }
}
