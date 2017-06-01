<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Resolver;

use Closure;

/**
 * States are used when classes want to resolve themselves
 *
 * @package  Cradle
 * @category Resolver
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait StateTrait
{
    use ResolverTrait;

    /**
     * Returns a state that was previously saved
     *
     * @param *string $key the state name
     *
     * @return mixed
     */
    public function loadState(string $name)
    {
        $state = $this->resolve($name);

        if (is_callable($state)) {
            if ($state instanceof Closure) {
                $state = $state->bindTo($this, get_class($this));
            }

            $state = call_user_func($state, $this);
        }

        return $state;
    }

    /**
     * Sets instance state for later usage.
     *
     * @param *string $name  the state name
     * @param mixed   $value the instance to save
     *
     * @return StateTrait
     */
    public function saveState(string $name, $value = null)
    {
        if (is_null($value)) {
            $value = $this;
        }

        $this->addResolver($name, function () use ($value) {
            return $value;
        });

        return $this;
    }
}
