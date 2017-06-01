<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Profiler;

/**
 * When developing this would be useful to
 * find the contents of any object or raw data type
 *
 * @package  Cradle
 * @category Core
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait InspectorTrait
{
    /**
     * @var Resolver|null $inspectorHandler The inspector instance
     */
    protected $inspectorHandler = null;

    /**
     * Returns the inspector handler
     *
     * @return InspectorHandler
     */
    public function getInspectorHandler(): InspectorHandler
    {
        if (is_null($this->inspectorHandler)) {
            $this->inspectorHandler = new InspectorHandler();
        }

        return $this->inspectorHandler;
    }

    /**
     * Force outputs any class property
     *
     * @param mixed       $variable name or value to be inspected
     * @param string|null $next     the name of the next available variable
     *
     * @return InspectTrait
     */
    public function inspect($variable = null, string $next = null)
    {
        //we are using tool in all cases
        $class = get_class($this);

        $inspector = $this->getInspectorHandler();

        //if variable is null
        if (is_null($variable)) {
            //output the class
            $inspector
                ->output(sprintf(InspectorHandler::INSPECT, $class))
                ->output($this);

            return $this;
        }

        //if variable is true
        if ($variable === true) {
            //return whatever the next response is
            //or return the next specified variable
            return $inspector->next($this, $next);
        }

        //if variable is not a string
        if (!is_string($variable)) {
            //output variable
            $inspector
                ->output(sprintf(InspectorHandler::INSPECT, 'Variable'))
                ->output($variable);

            return $this;
        }

        //if variable is set
        if (isset($this->$variable)) {
            //output it
            $inspector
                ->output(sprintf(InspectorHandler::INSPECT, $class.'->'.$variable))
                ->output($this->$variable);

            return $this;
        }

        //any other case output variable
        $inspector
            ->output(sprintf(InspectorHandler::INSPECT, 'Variable'))
            ->output($variable);

        return $this;
    }

    /**
     * Sets the inspector handler
     *
     * @param *InspectorInterface $inspector
     *
     * @return InspectorTrait
     */
    public function setInspectorHandler(InspectorInterface $inspector)
    {
        $this->inspectorHandler = $inspector;
        return $this;
    }
}
