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
 * These sets of callbacks are called when an error has occurred
 *
 * @package  Cradle
 * @category Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait ErrorProcessorTrait
{
    /**
     * @var Middleware|null $errorProcessor
     */
    protected $errorProcessor = null;

    /**
     * Returns an error handler object
     *
     * @return MiddlewareInterface
     */
    public function getErrorProcessor(): MiddlewareInterface
    {
        if (is_null($this->errorProcessor)) {
            if (method_exists($this, 'resolve')) {
                $this->setErrorProcessor($this->resolve(Middleware::class));
            } else {
                $this->setErrorProcessor(new Middleware());
            }
        }

        return $this->errorProcessor;
    }

    /**
     * Adds middleware
     *
     * @param *callable $callback The middleware handler
     *
     * @return ErrorProcessorTrait
     */
    public function error(callable $callback)
    {
        if ($callback instanceof Closure) {
            $callback = $callback->bindTo($this, get_class($this));
        }

        $this->getErrorProcessor()->register($callback);
        return $this;
    }

    /**
     * Sets the middleware to use on error
     *
     * @param *MiddlewareInterface $middleare
     *
     * @return ErrorProcessorTrait
     */
    public function setErrorProcessor(MiddlewareInterface $middleware)
    {
        $this->errorProcessor = $middleware;

        return $this;
    }
}
