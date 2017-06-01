<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Http\Request;

/**
 * Designed for the Request Object; Adds methods to store $_GET data
 *
 * @vendor   Cradle
 * @package  Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait GetTrait
{
    /**
     * Returns $_GET given name or all $_GET
     *
     * @param mixed ...$args
     *
     * @return mixed
     */
    public function getGet(...$args)
    {
        return $this->get('get', ...$args);
    }

    /**
     * Removes $_GET given name or all $_GET
     *
     * @param mixed ...$args
     *
     * @return GetTrait
     */
    public function removeGet(...$args)
    {
        return $this->remove('get', ...$args);
    }

    /**
     * Returns true if has $_GET given name or if $_GET is set
     *
     * @param mixed ...$args
     *
     * @return bool
     */
    public function hasGet(...$args): bool
    {
        return $this->exists('get', ...$args);
    }

    /**
     * Sets $_GET
     *
     * @param *array $data
     * @param mixed  ...$args
     *
     * @return GetTrait
     */
    public function setGet($data, ...$args)
    {
        if (is_array($data)) {
            return $this->set('get', $data);
        }

        if (count($args) === 0) {
            return $this;
        }

        return $this->set('get', $data, ...$args);
    }
}
