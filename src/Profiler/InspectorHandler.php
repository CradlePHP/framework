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
 * Used to inspect classes and result sets
 *
 * @package  Cradle
 * @category Core
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class InspectorHandler implements InspectorInterface
{
    /**
     * @const int INSPECT Output template
     */
    const INSPECT = 'INSPECTING %s:';

    /**
     * @var object|null $scope the inspected instance
     */
    protected $scope = null;

    /**
     * @var string|null $name name of method to be used before/after inspection
     */
    protected $name  = null;

    /**
     * Call a method of the scope and output it
     *
     * @param *string $name the name of the method
     * @param *array  $args arguments that were passed
     *
     * @return mixed
     */
    public function __call(string $name, array $args)
    {
        //if the scope is null
        if (is_null($this->scope)) {
            //just call the parent
            return null;
        }

        //get the results from the method call
        $results = $this->getResults($name, $args);

        //set temp variables
        $name = $this->name;
        $scope = $this->scope;

        //reset globals
        $this->name = null;
        $this->scope = null;

        //if there's a property name
        if ($name) {
            //output that
            $scope->inspect($name);
            //and return the results
            return $results;
        }

        //at this point we should output the results
        $class = get_class($scope);

        $output = sprintf(self::INSPECT, $class.'->'.$name);

        $this->output($output)->output($results);

        //and return the results
        return $results;
    }

    /**
     * Hijacks the class and reports the results of the next
     * method call
     *
     * @param *object     $scope the class instance
     * @param string|null $name  the name of the property to inspect
     *
     * @return InspectorInterface
     */
    public function next($scope, string $name = null): InspectorInterface
    {
        $this->scope = $scope;
        $this->name = $name;

        return $this;
    }

    /**
     * Outputs anything
     *
     * @param *mixed $variable any data
     *
     * @return InspectorInterface
     */
    public function output($variable): InspectorInterface
    {
        if ($variable === true) {
            $variable = '*TRUE*';
        } else if ($variable === false) {
            $variable = '*FALSE*';
        } else if (is_null($variable)) {
            $variable = '*null*';
        }

        echo '<pre>'.print_r($variable, true).'</pre>';
        return $this;
    }

    /**
     * Virtually calls the scope's method considering routes
     *
     * @param *string $name the name of the method
     * @param *array  $args arguments to pass
     *
     * @return mixed
     */
    protected function getResults(string $name, array $args)
    {
        if (method_exists($this->scope, $name)) {
            return call_user_func_array([$this->scope, $name], $args);
        }

        return $this->scope->__call($name, $args);
    }
}
