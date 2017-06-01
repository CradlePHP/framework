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
 * HTTP Request Object
 *
 * @vendor   Cradle
 * @package  Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
interface RequestInterface
{
    /**
     * Returns CLI args if any
     *
     * @return array|null
     */
    public function getArgs();

    /**
     * Returns final input stream
     *
     * @return string|null
     */
    public function getContent();

    /**
     * Returns $_COOKIE given name or all $_COOKIE
     *
     * @param mixed $args
     *
     * @return mixed
     */
    public function getCookies(...$args);

    /**
     * Returns $_FILES data given name or all $_FILES
     *
     * @param mixed $args
     *
     * @return mixed
     */
    public function getFiles(...$args);

    /**
     * Returns $_GET data given name or all $_GET
     *
     * @param mixed $args
     *
     * @return mixed
     */
    public function getGet(...$args);

    /**
     * Returns method if set
     *
     * @return string|null
     */
    public function getMethod();

    /**
     * Returns path data given name or all path data
     *
     * @param string|null $name The key name in the path (string|array)
     *
     * @return string|array
     */
    public function getPath(string $name = null);

    /**
     * Returns $_POST data given name or all $_POST data
     *
     * @param mixed $args
     *
     * @return mixed
     */
    public function getPost(...$args);

    /**
     * Returns string query if set
     *
     * @return string|null
     */
    public function getQuery();

    /**
     * Returns $_SERVER data given name or all $_SERVER data
     *
     * @param string|null $name The key name in the SERVER
     *
     * @return mixed
     */
    public function getServer(string $name = null);

    /**
     * Returns $_SESSION data given name or all $_SESSION data
     *
     * @param mixed $args
     *
     * @return mixed
     */
    public function getSession(...$args);

    /**
     * Returns true if has content
     *
     * @return bool
     */
    public function hasContent(): bool;

    /**
     * Returns true if has $_COOKIE given name or if $_COOKIE is set
     *
     * @param mixed $args
     *
     * @return bool
     */
    public function hasCookies(...$args): bool;

    /**
     * Returns true if has $_FILES given name or if $_FILES is set
     *
     * @param mixed $args
     *
     * @return bool
     */
    public function hasFiles(...$args): bool;

    /**
     * Returns true if has $_GET given name or if $_GET is set
     *
     * @param mixed $args
     *
     * @return bool
     */
    public function hasGet(...$args): bool;

    /**
     * Returns true if has $_POST given name or if $_POST is set
     *
     * @param mixed $args
     *
     * @return bool
     */
    public function hasPost(...$args): bool;

    /**
     * Returns true if has $_SERVER given name or if $_SERVER is set
     *
     * @param string|null $name The key name in the SERVER
     *
     * @return bool
     */
    public function hasServer(string $name = null): bool;

    /**
     * Returns true if has $_SESSION given name or if $_SESSION is set
     *
     * @param mixed $args
     *
     * @return bool
     */
    public function hasSession(...$args): bool;

    /**
     * Returns true if method is the one given
     *
     * @param *string $method
     *
     * @return bool
     */
    public function isMethod(string $method): bool;

    /**
     * Sets CLI args
     *
     * @param array|null
     *
     * @return RequestInterface
     */
    public function setArgs(array $argv = null);

    /**
     * Sets content
     *
     * @param *mixed $content
     *
     * @return RequestInterface
     */
    public function setContent($content);

    /**
     * Sets $_COOKIE
     *
     * @param *mixed $cookies
     *
     * @return RequestInterface
     */
    public function setCookies($data, ...$args);

    /**
     * Sets $_FILES
     *
     * @param *mixed $files
     *
     * @return RequestInterface
     */
    public function setFiles($data, ...$args);

    /**
     * Sets $_GET
     *
     * @param *mixed $get
     *
     * @return RequestInterface
     */
    public function setGet($data, ...$args);

    /**
     * Sets request method
     *
     * @param *string $method
     *
     * @return RequestInterface
     */
    public function setMethod(string $method);

    /**
     * Sets path given in string or array form
     *
     * @param *string|array $path
     *
     * @return RequestInterface
     */
    public function setPath($path);

    /**
     * Sets $_POST
     *
     * @param *array $post
     *
     * @return RequestInterface
     */
    public function setPost($data, ...$args);

    /**
     * Sets query string
     *
     * @param *string $get
     *
     * @return RequestInterface
     */
    public function setQuery($query);

    /**
     * Sets a request route
     *
     * @param *mixed $results
     *
     * @return RequestInterface
     */
    public function setRoute(array $route);

    /**
     * Sets $_SERVER
     *
     * @param *array $server
     *
     * @return RequestInterface
     */
    public function setServer(array $server);

    /**
     * Sets $_SESSION
     *
     * @param *mixed $session
     *
     * @return RequestInterface
     */
    public function setSession($data, ...$args);
}
