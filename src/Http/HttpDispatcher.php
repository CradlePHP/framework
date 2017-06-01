<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Http;

use Closure;
use Cradle\Http\Dispatcher\DispatcherInterface;
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
class HttpDispatcher implements DispatcherInterface
{
    /**
     * @const string HEADER_CONNECTION_CLOSE Template for closing
     */
    const HEADER_CONNECTION_CLOSE = "Connection: close\r\n";

    /**
     * @const string HEADER_CONTENT_ENCODING Template for encoding
     */
    const HEADER_CONTENT_ENCODING = "Content-Encoding: none\r\n";

    /**
     * @const string HEADER_CONTENT_LENGTH Template for length
     */
    const HEADER_CONTENT_LENGTH = 'Content-Length: %s';

    /**
     * @var bool $successful If we were able to output it
     */
    protected $successful = false;

    /**
     * @var array $mapCache The global curl callback
     */
    protected static $mapCache = [];

    /**
     * @var array $map The actual response callbacks
     */
    protected $map = [];

    /**
     * Set response maps, which is usually good for testing
     *
     * @param Closure $output
     * @param Closure $redirect
     */
    public function __construct(Closure $output = null, Closure $redirect = null)
    {
        if (empty(self::$mapCache)) {
            self::$mapCache['output'] = include(__DIR__ . '/map/output.php');
            self::$mapCache['redirect'] = include(__DIR__ . '/map/redirect.php');
        }

        $this->map = self::$mapCache;

        if (!is_null($output)) {
            $this->map['output'] = $output;
        }

        if (!is_null($redirect)) {
            $this->map['redirect'] = $redirect;
        }
    }

    /**
     * Starts to process the request
     *
     * @param ResponseInterface $response The response object to evaluate
     * @param bool              $emulate  If you really want it to echo (for testing)
     *
     * @return array with request and response inside
     */
    public function dispatch(ResponseInterface $response, bool $emulate = false)
    {
        $redirect = $response->getHeaders('Location');

        if ($redirect) {
            return $this->redirect($redirect, false, $emulate);
        }

        if (!$response->hasContent() && !$response->hasJson()) {
            $response->setStatus(404, '404 Not Found');

            //throw an exception
            throw HttpException::forResponseNotFound();
        }

        if (!$response->hasContent() && $response->hasJson()) {
            $response->addHeader('Content-Type', 'text/json');
            $response->setContent($response->get('json'));
        }

        if (!$response->getHeaders('Content-Type')) {
            $response->addHeader('Content-Type', 'text/html; charset=utf-8');
        }

        return $this->output($response, $emulate);
    }

    /**
     * Evaluates the response in order to determine the
     * output. Then of course, output it
     *
     * @param ResponseInterface $response The response object to evaluate
     * @param bool              $emulate  If you really want it to echo (for testing)
     *
     * @return HttpHandler
     */
    public function output(ResponseInterface $response, bool $emulate = false)
    {
        $code = $response->getStatus();
        $headers = $response->getHeaders();
        $body = $response->getContent();

        //make sure it's a string
        $body = (string) $body;

        if ($emulate) {
            $this->successful = true;
            return $body;
        }

        //now map it out
        call_user_func($this->map['output'], $code, $headers, $body);

        $this->successful = true;

        return $this;
    }

    /**
     * Browser redirect
     *
     * @param *string $path  Where to redirect to
     * @param bool    $force Whether if you want to exit immediately
     * @param bool    $emulate  If you really want it to redirect (for testing)
     *
     * @return HttpDispatcher
     */
    public function redirect(
        string $path,
        bool $force = false,
        bool $emulate = false
    ) {
        if ($emulate) {
            return $path;
        }

        //now map it out
        call_user_func($this->map['redirect'], $path, $force);

        return $this;
    }

    /**
     * Returns true if we were able to output
     * something
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->successful;
    }
}
