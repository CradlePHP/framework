<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace

{
    use Cradle\Framework\App;

    if (!function_exists('cradle')) {
        /**
         * The starting point of framework.
         *
         * Usage:
         * `cradle()`
         * - returns the static (global) handler
         *
         * `cradle(function() {})`
         * - returns the function
         * - used for scopes
         *
         * `cradle('global')`
         * - returns the global package
         * - you can use any registered package
         *
         * `cradle(Controller::class, 1, 2, 3)`
         * - Instantiates the given class
         * - with the following arguments
         *
         * @param mixed ...$args
         *
         * @return mixed
         */
        function cradle(...$args)
        {
            static $framework = null;

            //if no framework set
            if (is_null($framework)) {
                //set a new framework
                $framework = new App;
            }

            //if no arguments
            if (func_num_args() == 0) {
                //return the static framework
                return $framework;
            }

            //if the first argument is callable
            if (is_callable($args[0])) {
                //call it
                $callback = array_shift($args);

                if ($callback instanceof Closure) {
                    $callback = $callback->bindTo(
                        $framework,
                        get_class($framework)
                    );
                }

                //and return the results
                return call_user_func_array($callback, $args);
            }

            //it could be a package
            if (count($args) === 1
                && is_string($args[0])
                && $framework->isPackage($args[0])
            ) {
                //yay, return it
                return $framework->package($args[0]);
            }

            //not sure what else would be useful
            //so lets just resolve things...
            return $framework->resolve(...$args);
        }
    }
}

namespace Cradle\Framework

{
    /**
     * When you do add in your file:
     * `Cradle\Framework\Decorator::DECORATE;`
     *
     * It will enable the `cradle()` to be called
     * I know its hax0r...
     *
     * @package  Cradle
     * @category Framework
     * @author   Christian Blanquera <cblanquera@openovate.com>
     * @standard PSR-2
     */
    class Decorator
    {
        /**
         * @const int DECORATE
         */
        const DECORATE = 1;
    }
}
