<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Http\Dispatcher;

use Cradle\Http\HttpHandler;
use Cradle\Http\Response\ResponseInterface;

/**
 * This deals with the releasing of content into the
 * main output buffer. Considers headers and post processing
 *
 * @vendor   Cradle
 * @package  Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
interface DispatcherInterface
{
    /**
     * Evaluates the response in order to determine the
     * output. Then of course, output it
     *
     * @param ResponseInterface $response The response object to evaluate
     * @param bool              $emulate  If you really want it to echo (for testing)
     *
     * @return HttpHandler
     */
    public function output(ResponseInterface $response, bool $emulate = false);

    /**
     * Starts to process the request
     *
     * @param ResponseInterface $response The response object to evaluate
     * @param bool              $emulate  If you really want it to echo (for testing)
     *
     * @return array with request and response inside
     */
    public function dispatch(ResponseInterface $response, bool $emulate = false);

    /**
     * Browser redirect
     *
     * @param *string $path  Where to redirect to
     * @param bool    $force Whether if you want to exit immediately
     * @param bool    $emulate  If you really want it to redirect (for testing)
     */
    public function redirect(string $path, bool $force = false, bool $emulate = false);

    /**
     * Returns if we were able to output
     * something
     *
     * @return bool
     */
    public function isSuccessful(): bool;
}
