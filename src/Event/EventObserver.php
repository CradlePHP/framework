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
 * Event observer object
 *
 * @package  Cradle
 * @category Event
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class EventObserver
{
    /**
     * @var string $id Generated ID
     */
    protected $id = null;
    
    /**
     * @var callable $callback The observer callback
     */
    protected $callback = null;
    
    /**
     * We need a callback
     *
     * @param *callable $callback
     */
    public function __construct($callback)
    {
        $this->setCallback($callback);
    }
    
    /**
     * You can add a different callback if you want
     *
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }
    
    /**
     * You can add a different callback if you want
     *
     * @param *callable $callback
     *
     * @return EventObserver
     */
    public function setCallback($callback)
    {
        if (!is_callable($callback)) {
            throw EventException::forInvalidCallback();
        }
        
        $this->callback = $callback;
        $this->id = $this->getId($callback);
        
        return $this;
    }
    
    /**
     * Checks to see if the callback passed is the one here
     *
     * @param *callable $callback
     *
     * @return bool
     */
    public function assertEquals($callback)
    {
        if (!is_callable($callback)) {
            throw EventException::forInvalidCallback();
        }

        $id = $this->getId($callback);

        return $this->id === $id;
    }

    /**
     * Tries to generate an ID for a callable.
     * We need to try in order to properly unlisten
     * to a variable
     *
     * @param *callable $callback the callback function
     *
     * @return string|false
     */
    protected function getId($callback)
    {
        if (is_array($callback)) {
            if (isset($callback[0]) && is_object($callback[0])) {
                $callback[0] = spl_object_hash($callback[0]);
            }

            return $callback[0].'::'.$callable[1];
        }
        
        if ($callback instanceof Closure) {
            return spl_object_hash($callback);
        }
        
        if (is_string($callback)) {
            return $callback;
        }
        
        //it would technically never get to this point but oh well
        return md5(var_export($callback, true));
    }
}
