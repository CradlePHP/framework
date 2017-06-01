<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Data;

use Closure;
use ArrayAccess;
use Iterator;
use Countable;

use Cradle\Data\Collection\CollectionInterface;
use Cradle\Data\Collection\CollectionException;

use Cradle\Data\Model\ModelInterface;

use Cradle\Event\EventTrait;

use Cradle\Helper\BinderTrait;
use Cradle\Helper\InstanceTrait;
use Cradle\Helper\LoopTrait;
use Cradle\Helper\ConditionalTrait;

use Cradle\Profiler\InspectorTrait;
use Cradle\Profiler\LoggerTrait;

use Cradle\Resolver\StateTrait;
use Cradle\Resolver\ResolverException;

/**
 * Collections are a managable list of models. Model
 * methods called by the collection are simply passed
 * to each model in the collection. Collections perform
 * the same functionality as a model, except on a more
 * massive level. This is the main collection object.
 *
 * @package  Cradle
 * @category Date
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Collection implements ArrayAccess, Iterator, Countable, CollectionInterface
{
    use ArrayAccessTrait,
        CountableTrait,
        GeneratorTrait,
        IteratorTrait,
        EventTrait,
        InstanceTrait,
        LoopTrait,
        ConditionalTrait,
        InspectorTrait,
        LoggerTrait,
        StateTrait;

    /**
     * @const string FIRST Flag that designates the first in the collection
     */
    const FIRST = 'first';

    /**
     * @const string LAST Flag that designates the last in the collection
     */
    const LAST = 'last';

    /**
     * @var array $data The raw collection list
     */
    protected $data = [];

    /**
     * The magic behind setN and getN
     *
     * @param *string $name Name of method
     * @param *array  $args Arguments to pass
     *
     * @return mixed
     */
    public function __call(string $name, array $args)
    {
        //if the method starts with get
        if (strpos($name, 'get') === 0) {
            //getUserName('-') - get all rows column values
            $value = isset($args[0]) ? $args[0] : null;

            $data = [];
            //for each row
            foreach ($this->data as $i => $row) {
                //just add the column they want
                //let the model worry about the rest
                $data[] = $row->$name(isset($args[0]) ? $args[0] : null);
            }

            return $data;

        //if the method starts with set
        } else if (strpos($name, 'set') === 0) {
            //setUserName('Chris', '-') - set all user names to Chris
            $value         = isset($args[0]) ? $args[0] : null;
            $separator     = isset($args[1]) ? $args[1] : null;

            //for each row
            foreach ($this->data as $i => $row) {
                //just call the method
                //let the model worry about the rest
                $row->$name($value, $separator);
            }

            return $this;
        }

        $found = false;

        //for an array of models the method might exist
        //we should loop and check for a valid method
        foreach ($this->data as $i => $row) {
            //if no method exists
            if (!method_exists($row, $name)) {
                continue;
            }

            $found = true;

            //just call the method
            //let the model worry about the rest
            $row->$name(...$args);
        }

        //if found, it means something happened
        if ($found) {
            //so it was successful
            return $this;
        }

        try {
            return $this->__callResolver($name, $args);
        } catch (ResolverException $e) {
            throw new CollectionException($e->getMessage());
        }
    }

    /**
     * Presets the collection
     *
     * @param *array $data The initial data
     */
    public function __construct(array $data = [])
    {
        $this->set($data);
    }

    /**
     * Allow object property magic to redirect to the data variable
     *
     * @param *string $name  The name of the supposed property
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        //set all rows with this column and value
        $data = [];

        foreach ($this->data as $i => $row) {
            $data[$i] = $row[$name];
        }

        return $data;
    }

    /**
     * Allow a property for each row to be changed in one call
     *
     * @param *string $name  The name of the supposed property
     * @param *mixed  $value The value of the supposed property
     */
    public function __set(string $name, $value)
    {
        //set all rows with this column and value
        foreach ($this->data as $i => $row) {
            $row[$name] = $value;
        }
    }

    /**
     * If we output this to string we should see it as json
     *
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->get(), JSON_PRETTY_PRINT);
    }

    /**
     * Adds a row to the collection
     *
     * @param array|object $row a row in the form of an array or accepted model
     *
     * @return CollectionInterface
     */
    public function add($row = []): CollectionInterface
    {
        //if it's an array
        if (is_array($row)) {
            //make it a model
            $row = $this->getModel($row);
        }

        //add it now
        $this->data[] = $row;

        return $this;
    }

    /**
     * Removes a row and reindexes the collection
     *
     * @param string|int $index The position in the collection to cut out
     *
     * @return CollectionInterface
     */
    public function cut($index = self::LAST): CollectionInterface
    {
        //if index is first
        if ($index == self::FIRST) {
            //we really mean 0
            $index = 0;
        //if index is last
        } else if ($index == self::LAST) {
            //we realy mean the last index number
            $index = count($this->data) -1;
        }

        //if this row is found
        if (isset($this->data[$index])) {
            //unset it
            unset($this->data[$index]);
        }

        //reindex the list
        $this->data = array_values($this->data);

        return $this;
    }

    /**
     * Loops through returned result sets
     *
     * @param *function $callback The handler to call on each iteration
     *
     * @return CollectionInterface
     */
    public function each(callable $callback): CollectionInterface
    {
        if ($callback instanceof Closure) {
            $callback = $this->bindCallback($callback);
        }

        foreach ($this->generator() as $key => $value) {
            call_user_func($callback, $key, $value);
        }

        return $this;
    }

    /**
     * Returns the entire data
     *
     * @return array
     */
    public function get(): array
    {
        $data = [];

        foreach ($this->data as $row) {
            $data[] = $row->get();
        }

        return $data;
    }

    /**
     * Returns the entire data
     *
     * @param array $row
     *
     * @return ModelInterface
     */
    public function getModel(array $row = []): ModelInterface
    {
        return $this->resolve(Model::class, $row);
    }

    /**
     * Sets the entire data
     *
     * @param *array $data
     *
     * @return CollectionInterface
     */
    public function set(array $data): CollectionInterface
    {
        foreach ($data as $row) {
            $this->add($row);
        }

        return $this;
    }
}
