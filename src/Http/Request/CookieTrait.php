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
 * Designed for the Request Object; Adds methods to store $_COOKIE data
 *
 * @vendor   Cradle
 * @package  Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait CookieTrait
{
    /**
     * Returns $_COOKIE given name or all $_COOKIE
     *
     * @param mixed ...$args
     *
     * @return mixed
     */
    public function getCookies(...$args)
    {
        return $this->get('cookie', ...$args);
    }

    /**
     * Removes $_COOKIE given name or all $_COOKIE
     *
     * @param mixed ...$args
     *
     * @return CookieTrait
     */
    public function removeCookies(...$args)
    {
        return $this->remove('cookie', ...$args);
    }

    /**
     * Returns true if has $_COOKIE given name or if $_COOKIE is set
     *
     * @param mixed ...$args
     *
     * @return bool
     */
    public function hasCookies(...$args): bool
    {
        return $this->exists('cookie', ...$args);
    }

    /**
     * Sets $_COOKIE
     *
     * @param *mixed $data
     * @param mixed  ...$args
     *
     * @return CookieTrait
     */
    public function setCookies($data, ...$args)
    {
        if (is_array($data)) {
            return $this->set('cookie', $data);
        }

        if (count($args) === 0) {
            return $this;
        }

        return $this->set('cookie', $data, ...$args);
    }
}
