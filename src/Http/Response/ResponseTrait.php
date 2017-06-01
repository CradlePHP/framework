<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Http\Response;

use Cradle\Http\Response;

/**
 * Designed for the HttpHandler we are parting this out
 * to lessen the confusion
 *
 * @package  Cradle
 * @category Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait ResponseTrait
{
    /**
     * @var Response|null $response Response object to use
     */
    protected $response = null;

    /**
     * Returns a response object
     *
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        if (is_null($this->response)) {
            if (method_exists($this, 'resolve')) {
                $this->setResponse($this->resolve(Response::class)->load());
            } else {
                $response = new Response();
                $this->setResponse($response->load());
            }
        }

        return $this->response;
    }

    /**
     * Sets the response object to use
     *
     * @param ResponseInterface $response
     *
     * @return ResponseTrait
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;

        return $this;
    }
}
