<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Framework;

use Cradle\Resolver\ResolverTrait;

/**
 * Trait for controller classes in packages or custom
 *
 * @vendor   Cradle
 * @package  Framework
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait FlowTrait
{
    use ResolverTrait;

    /**
     * @var array $actions
     */
    protected $actions = array();

    /**
     * @var array $current
     */
    protected $current = null;

    /**
     * Returns the action property
     * example `Flow::auth()->load()`
     * example `Flow::auth()->search()->load()`
     * example `Flow::auth()->search->load()`
     *
     * @param *string $name
     * @param *array  $args
     *
     * @return string
     */
    public function __callFlow($name, $args)
    {
        if (is_null($this->current)) {
            if (!isset($this->actions[$name])) {
                return $this;
            }

            //its the search part in `Flow::auth()->search()->load()`
            $this->current = $this->actions[$name];
            return $this;
        }

        //its the load part in `Flow::auth()->search()->load()`
        $action = $this->current;
        $this->current = null;

        if (property_exists($action, $name)) {
            return $action->$name;
        }

        return function ($request, $response) use ($action, $name, &$args) {
            //we should throw a method exist error at runtime
            $results = $action->$name($request, $response, ...$args);

            if ($results instanceof $action) {
                return;
            }

            return $results;
        };
    }

    /**
     * Returns the action property
     * example `Flow::auth()->search->load`
     *
     * @param *string $name
     *
     * @return string|callable
     */
    public function __getFlow($name)
    {
        return $this->__callFlow($name, array());
    }
}
