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
 * Designed for the Request Object; Adds methods to store $_POST data
 *
 * @vendor   Cradle
 * @package  Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait PostTrait
{
    /**
     * Returns $_POST given name or all $_POST
     *
     * @param mixed ...$args
     *
     * @return mixed
     */
    public function getPost(...$args)
    {
        return $this->get('post', ...$args);
    }

    /**
     * Removes $_POST given name or all $_POST
     *
     * @param mixed ...$args
     *
     * @return PostTrait
     */
    public function removePost(...$args)
    {
        return $this->remove('post', ...$args);
    }

    /**
     * Returns true if has $_POST given name or if $_POST is set
     *
     * @param mixed ...$args
     *
     * @return bool
     */
    public function hasPost(...$args): bool
    {
        return $this->exists('post', ...$args);
    }

    /**
     * Sets $_POST
     *
     * @param *mixed $data
     * @param mixed  ...$args
     *
     * @return PostTrait
     */
    public function setPost($data, ...$args)
    {
        if (is_array($data)) {
            return $this->set('post', $data);
        }

        if (count($args) === 0) {
            return $this;
        }

        return $this->set('post', $data, ...$args);
    }
}
