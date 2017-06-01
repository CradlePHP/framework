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
 * Designed for the Request Object; Adds methods to store $_SERVER data
 *
 * @vendor   Cradle
 * @package  Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait ServerTrait
{
    /**
     * Returns method if set
     *
     * @return string|null
     */
    public function getMethod()
    {
        return strtoupper($this->get('method'));
    }

    /**
     * Returns path data given name or all path data
     *
     * @param string|null $name The key name in the path (string|array)
     *
     * @return string|array
     */
    public function getPath(string $name = null)
    {
        if (is_null($name)) {
            return $this->get('path');
        }

        return $this->get('path', $name);
    }

    /**
     * Returns string query if set
     *
     * @return string|null
     */
    public function getQuery()
    {
        return $this->get('query');
    }

    /**
     * Returns SERVER data given name or all SERVER data
     *
     * @param string|null $name The key name in the SERVER
     *
     * @return mixed
     */
    public function getServer(string $name = null)
    {
        if (is_null($name)) {
            return $this->get('server');
        }

        return $this->get('server', $name);
    }

    /**
     * Returns SERVER data given name or all SERVER data
     *
     * @param string|null $name The key name in the SERVER
     *
     * @return bool
     */
    public function hasServer(string $name = null): bool
    {
        if (is_null($name)) {
            return $this->exists('server');
        }

        return $this->exists('server', $name);
    }

    /**
     * Returns true if method is the one given
     *
     * @param *string $method
     *
     * @return bool
     */
    public function isMethod(string $method): bool
    {
        return strtoupper($method) === strtoupper($this->get('method'));
    }

    /**
     * Sets request method
     *
     * @param *string $method
     *
     * @return ServerTrait
     */
    public function setMethod(string $method)
    {
        return $this->set('method', $method);
    }

    /**
     * Sets path given in string or array form
     *
     * @param *string|array $path
     *
     * @return ServerTrait
     */
    public function setPath($path)
    {
        if (is_string($path)) {
            $array = explode('/', $path);
        } else if (is_array($path)) {
            $array = $path;
            $path = implode('/', $path);
        }

        return $this
            ->setDot('path.string', $path)
            ->setDot('path.array', $array);
    }

    /**
     * Sets query string
     *
     * @param *string $get
     *
     * @return ServerTrait
     */
    public function setQuery($query)
    {
        return $this->set('query', $query);
    }

    /**
     * Sets SERVER
     *
     * @param *array $server
     *
     * @return ServerTrait
     */
    public function setServer(array $server)
    {
        $this->set('server', $server);

        //if there is no path set
        if (!$this->exists('path') && isset($server['REQUEST_URI'])) {
            $path = $server['REQUEST_URI'];

            //remove ? url queries
            if (strpos($path, '?') !== false) {
                list($path, $tmp) = explode('?', $path, 2);
            }

            $this->setPath($path);
        }

        if (!$this->exists('method') && isset($server['REQUEST_METHOD'])) {
            $this->setMethod($server['REQUEST_METHOD']);
        }

        if (!$this->exists('query') && isset($server['QUERY_STRING'])) {
            $this->setQuery($server['QUERY_STRING']);
        }

        return $this;
    }
}
