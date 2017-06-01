<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Event;

/**
 * Allows the ability to listen to events made known by another
 * piece of functionality. Events are items that transpire based
 * on an action. With events you can add extra functionality
 * right after the event has triggered.
 *
 * @package  Cradle
 * @category Event
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
interface EventInterface
{
    /**
     * Stops listening to an event
     *
     * @param string|null   $event    name of the event
     * @param callable|null $callback callback handler
     *
     * @return EventInterface
     */
    public function off(string $event = null, callable $callback = null): EventInterface;

    /**
     * Attaches an instance to be notified
     * when an event has been triggered
     *
     * @param *string|array   $event    The name of the event
     * @param *callable       $callback The event handler
     * @param int             $priority Set the importance
     *
     * @return EventInterface
     */
    public function on($event, callable $callback, int $priority = 0): EventInterface;

    /**
     * Notify all observers of that a specific
     * event has happened
     *
     * @param *string $event The event to trigger
     * @param mixed   ...$args The arguments to pass to the handler
     *
     * @return EventInterface
     */
    public function trigger(string $event, ...$args): EventInterface;
}
