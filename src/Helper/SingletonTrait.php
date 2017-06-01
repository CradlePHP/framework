<?php

/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Helper;

/**
 * Instantiates a class without using `new`. This is used
 * primarly to start chaining immediately without assigning
 * this instance to a variable. This particularly follows a
 * singleton pattern. No singletons are used in this library
 *
 * @package  Cradle
 * @category Helper
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait SingletonTrait
{
    /**
     * @var array $instances captured sungleton instances
     */
    private static $instances = [];

    /**
     * One of the hard thing about instantiating classes is
     * that design patterns can impose different ways of
     * instantiating a class. The word "new" is not flexible.
     * Authors of classes should be able to control how a class
     * is instantiated, while leaving others using the class
     * oblivious to it. All in all its one less thing to remember
     * for each class call. By default we instantiate classes with
     * this method.
     *
     * @param mixed[,mixed..] $args Arguments to pass to the constructor
     *
     * @return SingletonTrait
     */
    public static function i()
    {
        $class = get_called_class();

        //if it's not set
        if (!isset(self::$instances[$class])) {
            //set it
            $args = func_get_args();
            self::$instances[$class] = new $class(...$args);
        }

        return self::$instances[$class];
    }
}
