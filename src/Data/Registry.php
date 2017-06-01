<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Data;

use ArrayAccess;
use Iterator;
use Countable;

use Cradle\Data\Registry\RegistryInterface;
use Cradle\Data\Registry\RegistryException;

use Cradle\Event\EventTrait;

use Cradle\Helper\InstanceTrait;
use Cradle\Helper\LoopTrait;
use Cradle\Helper\ConditionalTrait;

use Cradle\Profiler\InspectorTrait;
use Cradle\Profiler\LoggerTrait;

use Cradle\Resolver\StateTrait;
use Cradle\Resolver\ResolverException;

/**
 * Registry are designed to easily manipulate $data in
 * preparation to integrate with any multi dimensional
 * data store. This is the main registry object.
 *
 * @package  Cradle
 * @category Date
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Registry implements ArrayAccess, Iterator, Countable, RegistryInterface
{
    use DataTrait,
        EventTrait,
        InstanceTrait,
        LoopTrait,
        ConditionalTrait,
        InspectorTrait,
        LoggerTrait,
        StateTrait
        {
            DataTrait::__getData as __get;
            DataTrait::__setData as __set;
            DataTrait::__toStringData as __toString;
        }

    /**
     * Attempts to use __callData then __callResolver
     *
     * @param *string $name name of method
     * @param *array  $args arguments to pass
     *
     * @return mixed
     */
    public function __call(string $name, array $args)
    {
        try {
            return $this->__callData($name, $args);
        } catch (DataException $e) {
        }

        try {
            return $this->__callResolver($name, $args);
        } catch (ResolverException $e) {
            throw new RegistryException($e->getMessage());
        }
    }

    /**
     * Presets the collection
     *
     * @param array $data The initial data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Returns true if the path keys
     * exist in the dataset
     *
     * @param scalar|null ...$args Path keys
     *
     * @return bool
     */
    public function exists(...$args): bool
    {
        if (!count($args)) {
            //test to see if it's empty
            return !$this->isEmpty();
        }

        $separator = '--'. md5(uniqid()) . '--';
        return $this->isDot(implode($separator, $args), $separator);
    }

    /**
     * Returns the exact data given the path keys
     *
     * @param scalar|null ...$args Path keys
     *
     * @return mixed
     */
    public function get(...$args)
    {
        if (count($args) === 0) {
            return $this->data;
        }

        $separator = '--'. md5(uniqid()) . '--';
        return $this->getDot(implode($separator, $args), $separator);
    }

    /**
     * Returns true if the path keys
     * does not exist in the dataset
     * or if it has an empy value
     *
     * @param scalar|null ...$args Path keys
     *
     * @return bool
     */
    public function isEmpty(...$args): bool
    {
        if (!count($args)) {
            //test to see if it's empty
            return empty($this->data);
        }

        $separator = '--'. md5(uniqid()) . '--';

        $data = $this->getDot(implode($separator, $args), $separator);

        //if it's bool
        if (is_bool($data)) {
            //it's something
            return false;
        }

        //if it's scalar
        if (is_scalar($data)) {
            return !strlen($data);
        }

        return empty($data);
    }

    /**
     * Removes the data found in the path keys
     *
     * @param scalar|null ...$args Path keys
     *
     * @return Registry
     */
    public function remove(...$args): RegistryInterface
    {
        if (!count($args)) {
            //there's nothing to remove
            return $this;
        }

        $separator = '--'. md5(uniqid()) . '--';
        return $this->removeDot(implode($separator, $args), $separator);
    }

    /**
     * Sets the given data to given the path keys
     *
     * @param scalar|null ...$args Path keys and value on the end
     *
     * @return Registry
     */
    public function set(...$args): RegistryInterface
    {
        $separator = '--'. md5(uniqid()) . '--';

        switch (count($args)) {
            case 0:
                //there's nothing to set
                return $this;
            case 1:
                if (is_array($args[0])) {
                    $this->data = $args[0];
                }
                return $this;
            default:
                $value = array_pop($args);
                return $this->setDot(implode($separator, $args), $value, $separator);
        }
    }
}
