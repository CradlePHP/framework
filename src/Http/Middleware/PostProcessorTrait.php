<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Http\Middleware;

use Closure;
use Cradle\Http\Middleware;

/**
 * These sets of callbacks are called after the connection is closed
 *
 * @package  Cradle
 * @category Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait PostProcessorTrait
{
    /**
     * @var Middleware|null $preProcessor
     */
    protected $postProcessor = null;

    /**
     * Returns a middleware object
     *
     * @return MiddlewareInterface
     */
    public function getPostprocessor(): MiddlewareInterface
    {
        if (is_null($this->postProcessor)) {
            if (method_exists($this, 'resolve')) {
                $this->setPostprocessor($this->resolve(Middleware::class));
            } else {
                $this->setPostprocessor(new Middleware());
            }
        }

        return $this->postProcessor;
    }

    /**
     * Adds middleware
     *
     * @param *callable $callback The middleware handler
     *
     * @return PostProcessorTrait
     */
    public function postprocess(callable $callback)
    {
        if ($callback instanceof Closure) {
            $callback = $callback->bindTo($this, get_class($this));
        }

        $this->getPostprocessor()->register($callback);
        return $this;
    }

    /**
     * Sets the middleware to use
     *
     * @param *MiddlewareInterface $middleare
     *
     * @return PostProcessorTrait
     */
    public function setPostprocessor(MiddlewareInterface $middleware)
    {
        $this->postProcessor = $middleware;

        return $this;
    }
}
