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
 * Designed for the Request Object; Adds methods to store $_FILES data
 *
 * @vendor   Cradle
 * @package  Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait FileTrait
{
    /**
     * Returns $_FILES given name or all $_FILES
     *
     * @param mixed ...$args
     *
     * @return mixed
     */
    public function getFiles(...$args)
    {
        return $this->get('files', ...$args);
    }

    /**
     * Removes $_FILES given name or all $_FILES
     *
     * @param mixed ...$args
     *
     * @return FileTrait
     */
    public function removeFiles(...$args)
    {
        return $this->remove('files', ...$args);
    }

    /**
     * Returns true if has $_FILES given name or if $_FILES is set
     *
     * @param mixed ...$args
     *
     * @return bool
     */
    public function hasFiles(...$args): bool
    {
        return $this->exists('files', ...$args);
    }

    /**
     * Sets $_FILES
     *
     * @param *mixed $data
     * @param mixed  ...$args
     *
     * @return FileTrait
     */
    public function setFiles($data, ...$args)
    {
        if (is_array($data)) {
            return $this->set('files', $data);
        }

        if (count($args) === 0) {
            return $this;
        }

        return $this->set('files', $data, ...$args);
    }
}
