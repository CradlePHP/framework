<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Http\Response;

/**
 * Designed for the Response Object; Adds methods to process REST type responses
 *
 * @vendor   Cradle
 * @package  Server
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait PageTrait
{
    /**
     * Adds a page meta item
     *
     * @param *string $name
     * @param *string $content
     *
     * @return PageTrait
     */
    public function addMeta(string $name, string $content)
    {
        $args = func_get_args();

        return $this->set('page', 'meta', ...$args);
    }

    /**
     * Returns flash data
     *
     * @return array|null
     */
    public function getFlash()
    {
        return $this->get('page', 'flash');
    }

    /**
     * Returns meta given path or all meta data
     *
     * @param string ...$args
     *
     * @return string|array
     */
    public function getMeta(string ...$args)
    {
        return $this->get('page', 'meta', ...$args);
    }

    /**
     * Returns page data given path or all page data
     *
     * @param mixed ...$args
     *
     * @return string|array
     */
    public function getPage(...$args)
    {
        return $this->get('page', ...$args);
    }

    /**
     * Returns true if there's any page data
     *
     * @param mixed ...$args
     *
     * @return bool
     */
    public function hasPage(...$args): bool
    {
        if (!count($args)) {
            return $this->exists('page');
        }

        return $this->exists('page', ...$args);
    }

    /**
     * Removes arbitrary page data
     *
     * @param string ...$args
     *
     * @return PageTrait
     */
    public function removePage(...$args)
    {
        return $this->remove('page', ...$args);
    }

    /**
     * Sets a Page flash
     *
     * @param *string $message
     * @param string  $type
     *
     * @return PageTrait
     */
    public function setFlash(string $message, string $type = 'info')
    {
        return $this
            ->set('page', 'flash', 'message', $message)
            ->set('page', 'flash', 'type', $type);
    }

    /**
     * Sets arbitrary page data
     *
     * @param string ...$args
     *
     * @return PageTrait
     */
    public function setPage(...$args)
    {
        if (count($args) < 2) {
            return $this;
        }

        return $this->set('page', ...$args);
    }

    /**
     * Sets a Page title
     *
     * @param *string $title
     *
     * @return PageTrait
     */
    public function setTitle(string $title)
    {
        return $this->set('page', 'title', $title);
    }
}
