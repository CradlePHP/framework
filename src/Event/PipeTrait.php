<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Event;

use Closure;

/**
 * An event pipe executes a series of triggers
 * one after the other called flows. Sub flows
 * defined in the same call to help visualize
 * and group process flows.
 *
 * @vendor   Cradle
 * @package  Pipe
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait PipeTrait
{

    use EventTrait { EventTrait::trigger as triggerEvent;
    }

    /**
     * @var array $protocols Custom protocol callbacks
     */
    protected $protocols = [];

    /**
     * @var array $flows are short lived and volatile
     */
    protected static $flows = [];

    /**
     * Sets up a process flow
     *
     * @param *string         $name     The name of this rule
     * @param string|callable ...$flow  An event name or callback
     *
     * @return PipeTrait
     */
    public function flow(string $event, ...$flow)
    {
        //listen for the main on global
        $this->on($event, function (...$args) use (&$flow) {
            $this->triggerFlow($flow, ...$args);

            //analyze the meta
            $meta = $this->getEventHandler()->getMeta();

            //if meta is false
            if ($meta === false) {
                //we should also stop the flow
                return false;
            }
        });

        return $this;
    }

    /**
     * Adds a protocol used to custom parse an event name
     *
     * @param *string   $name The middleware handler
     * @param *callable $callback The middleware handler
     *
     * @return PipeTrait
     */
    public function protocol(string $name, callable $callback)
    {
        //create a space
        $this->protocols[$name] = $callback;

        return $this;
    }

    /**
     * Sets the subflow to be called
     * when there is an array fork
     *
     * @param string $event
     * @param mixed  ...$args
     *
     * @return PipeTrait
     */
    public function subflow(string $event, ...$args)
    {
        if (isset(self::$flows[$event])) {
            $this->triggerFlow(self::$flows[$event], ...$args);
        }

        return $this;
    }

    /**
     * Calls an event considering classes and protocols
     *
     * @param *string|callable $name
     * @param mixed            ...$args
     *
     * @return PipeTrait
     */
    public function trigger($name, ...$args)
    {
        //we should deal with strings
        //then callables respectively
        //to allow overriding
        if (is_string($name)) {
            //is it a protocol?
            if (strpos($name, '://') !== false) {
                return $this->triggerProtocol($name, ...$args);
            }

            //they can call a class
            if (strpos($name, '@') !== false) {
                return $this->triggerController($name, ...$args);
            }

            //unless it's a static call
            if (strpos($name, '::') !== false && is_callable($name)) {
                call_user_func_array($name, $args);
                return $this;
            }

            return $this->triggerEvent($name, ...$args);
        }

        if (is_callable($name)) {
            if ($name instanceof Closure) {
                $name = $this->bindCallback($name);
            }

            call_user_func_array($name, $args);
        }

        //we can only deal with callable and strings
        //we don't want to throw an error
        //because it could just be a pseudo
        //placeholder
        return $this;
    }

    /**
     * Calls a controller method
     *
     * @param *string $controller In the form of class@method
     * @param mixed   ...$args
     *
     * @return PipeTrait
     */
    public function triggerController(string $controller, ...$args)
    {
        //extract the class and method
        list($class, $method) = explode('@', $controller, 2);

        //if the class exists
        if (class_exists($class)) {
            //instantiate it
            $instance = new $class();

            //does the method exist ?
            if (method_exists($instance, $method)) {
                call_user_func_array([$instance, $method], $args);
            }
        }

        return $this;
    }

    /**
     * Separate trigger for flows
     * has nothing to do with `trigger()`
     *
     * @param *array $flow
     * @param mixed  ...$args
     *
     * @return PipeTrait
     */
    public function triggerFlow(array $flow, ...$args)
    {
        foreach ($flow as $i => $step) {
            //subflows will trigger separately
            if (is_array($step)) {
                continue;
            }

            //rule the subtasks first
            $j = 1;

            while (isset($flow[$i + $j]) && is_array($flow[$i + $j])) {
                //subflows should have
                //an event and a handler
                if (count($flow[$i + $j]) > 1) {
                    //the subflow is valid
                    //extract the event out
                    $event = array_shift($flow[$i + $j]);
                    self::$flows[$event] = $flow[$i + $j];
                }

                $j++;
            }

            //now trigger the event
            $this->trigger($step, ...$args);

            self::$flows = [];

            //analyze the meta
            $meta = $this->getEventHandler()->getMeta();

            //if meta is false
            if ($meta === false) {
                //we should also stop the flow
                break;
            }
        }

        return $this;
    }

    /**
     * Calls a protocol
     *
     * @param *string $protocol In the form of protocol://event
     * @param mixed   ...$args
     *
     * @return Base
     */
    public function triggerProtocol(string $protocol, ...$args)
    {
        list($protocol, $name) = explode('://', $protocol, 2);

        //if it's not a registered protocol
        if (!isset($this->protocols[$protocol])) {
            //oops?
            return $this;
        }

        //get the protocol
        $protocol = $this->protocols[$protocol];

        //we should deal with strings
        //then callables respectively
        //to allow overriding

        //they can call a class
        if (is_string($protocol) && strpos($protocol, '@') !== false) {
            return $this->triggerController($protocol, $name, ...$args);
        }

        //late binding ?
        if ($protocol instanceof Closure) {
            $protocol = $this->bindCallback($protocol);
        }

        if (is_callable($protocol)) {
            //call the protocol
            call_user_func($protocol, $name, ...$args);
        }

        //we can only deal with callable and strings
        //we don't want to throw an error
        //because it could just be a pseudo
        //placeholder
        return $this;
    }
}
