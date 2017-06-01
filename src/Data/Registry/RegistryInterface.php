<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Data\Registry;

/**
 * Registry are designed to easily manipulate $data in
 * preparation to integrate with any multi dimensional
 * data store. This interface is defined to support
 * depenancy injection.
 *
 * @package  Cradle
 * @category Data
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
interface RegistryInterface
{
    /**
     * Attempts to use __callData then __callResolver
     *
     * @param *string $name name of method
     * @param *array  $args arguments to pass
     *
     * @return mixed
     */
    public function __call(string $name, array $args);

    /**
     * Allow object property magic to redirect to the data variable
     *
     * @param *string $name  The name of the supposed property
     * @param *mixed  $value The value of the supposed property
     */
    public function __get(string $name);

    /**
     * Allow object property magic to redirect to the data variable
     *
     * @param *string $name  The name of the supposed property
     * @param *mixed  $value The value of the supposed property
     */
    public function __set(string $name, $value);

    /**
     * If we output this to string we should see it as json
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Returns the data size
     * For Countable interface
     */
    public function count(): int;

    /**
     * Returns the current item
     * For Iterator interface
     */
    public function current();

    /**
     * Loop generator
     */
    public function generator();

    /**
     * Returns true if the path keys
     * exist in the dataset
     *
     * @param scalar|null ...$args Path keys
     *
     * @return bool
     */
    public function exists(...$args): bool;

    /**
     * Returns the exact data given the path keys
     *
     * @param scalar|null ...$args Path keys
     *
     * @return mixed
     */
    public function get(...$args);

    /**
     * Returns true if the path keys
     * does not exist in the dataset
     * or if it has an empy value
     *
     * @param scalar|null ...$args Path keys
     *
     * @return bool
     */
    public function isEmpty(...$args): bool;

    /**
     * Returns th current position
     * For Iterator interface
     */
    public function key();

    /**
     * Increases the position
     * For Iterator interface
     */
    public function next();

    /**
     * isset using the ArrayAccess interface
     *
     * @param *scalar|null|bool $offset The key to test if exists
     *
     * @return bool
     */
    public function offsetExists($offset): bool;

    /**
     * returns data using the ArrayAccess interface
     *
     * @param *scalar|null|bool $offset The key to get
     *
     * @return mixed
     */
    public function offsetGet($offset);

    /**
     * Sets data using the ArrayAccess interface
     *
     * @param *scalar|null|bool $offset The key to set
     * @param mixed             $value  The value the key should be set to
     */
    public function offsetSet($offset, $value);

    /**
     * unsets using the ArrayAccess interface
     *
     * @param *scalar|null|bool $offset The key to unset
     */
    public function offsetUnset($offset);

    /**
     * Rewinds the position
     * For Iterator interface
     */
    public function rewind();

    /**
     * Removes the data found in the path keys
     *
     * @param scalar|null ...$args Path keys
     *
     * @return RegistryInterface
     */
    public function remove(...$args): RegistryInterface;

    /**
     * Sets the given data to given the path keys
     *
     * @param scalar|null ...$args Path keys and value on the end
     *
     * @return RegistryInterface
     */
    public function set(...$args): RegistryInterface;

    /**
     * Validates whether if the index is set
     * For Iterator interface
     *
     * @return bool
     */
    public function valid(): bool;
}
