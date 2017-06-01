<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Profiler;

use Closure;

/**
 * If you want to enable logging capabilities within your class
 *
 * @package  Cradle
 * @category Core
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait LoggerTrait
{
    /**
     * @var array $loggers
     */
    private $loggers = [];

    /**
     * Adds a logger callback when it happens
     *
     * @param *callable $callback
     *
     * @return LoggerTrait
     */
    public function addLogger(callable $callback)
    {
        if (!is_callable($callback)) {
            throw LoggerException::forInvalidCallback();
        }

        if ($callback instanceof Closure) {
            $callback = $callback->bindTo($this, get_class($this));
        }

        $this->loggers[] = $callback;

        return $this;
    }

    /**
     * Calls loggers passing arguments
     *
     * @param *mixed ...$args
     *
     * @return LoggerTrait
     */
    public function log(...$args)
    {
        foreach ($this->loggers as $callback) {
            call_user_func_array($callback, $args);
        }

        return $this;
    }
}
