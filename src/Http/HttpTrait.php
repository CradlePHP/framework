<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Http;

use Throwable;

use Cradle\Http\Router\RouterTrait;
use Cradle\Http\Request\RequestTrait;
use Cradle\Http\Response\ResponseTrait;
use Cradle\Http\Dispatcher\DispatcherTrait;
use Cradle\Http\Middleware\PreProcessorTrait;
use Cradle\Http\Middleware\PostProcessorTrait;
use Cradle\Http\Middleware\ErrorProcessorTrait;

/**
 * You can optionally adopt these methods into an existing class
 *
 * @vendor   Cradle
 * @package  Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
trait HttpTrait
{
    use DispatcherTrait,
        PreProcessorTrait,
        PostProcessorTrait,
        ErrorProcessorTrait,
        RequestTrait,
        ResponseTrait,
        RouterTrait;

    /**
     * Prepares the event and calls the preprocessors
     *
     * @return bool Whether if the process should continue
     */
    public function prepare()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

        try {
            //dispatch an init
            $continue = $this->getPreprocessor()->process($request, $response);
        } catch (Throwable $e) {
            //if there is an exception
            //you may not want to out
            //right throw it out
            $response->setStatus(500, HttpHandler::STATUS_500);
            $continue = $this->getErrorProcessor()->process($request, $response, $e);
            //if there's an error in the errorware then let it be thrown
        }

        return $continue;
    }
    
    /**
     * Handles the main routing process
     *
     * @return bool Whether if the process should continue
     */
    public function process()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        try {
            //dispatch an init
            $continue = $this->getRouter()->process($request, $response);
        } catch (Throwable $e) {
            //if there is an exception
            //you may not want to out
            //right throw it out
            $response->setStatus(500, HttpHandler::STATUS_500);
            $continue = $this->getErrorProcessor()->process($request, $response, $e);
            //if there's an error in the error processor then let it be thrown
        }
        
        return $continue;
    }
    
    /**
     * Process and output
     *
     * @param bool $emulate  If you really want it to echo (for testing)
     *
     * @return HttpHandler
     */
    public function render($emulate = false)
    {
        if (!$this->prepare() || !$this->process()) {
            return $this;
        }
        
        $response = $this->getResponse();
        
        $continue = true;

        if (!$response->hasContent() && !$response->hasJson()) {
            $request = $this->getRequest();
            $response->setStatus(404, HttpHandler::STATUS_404);
            
            $error = new HttpException(HttpHandler::STATUS_404, 404);
            
            $continue = $this->getErrorProcessor()->process($request, $response, $error);
        }
        
        if ($continue) {
            $this->getDispatcher()->dispatch($response, $emulate);
        }
        
        //the connection is already closed
        //also remember there are no more sessions
        $this->shutdown();
        
        return $this;
    }

    /**
     * This is called after it is outputted and the connection is closed
     *
     * @return bool Whether if the process should continue
     */
    public function shutdown()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        try {
            //dispatch an init
            $continue = $this->getPostprocessor()->process($request, $response);
        } catch (Throwable $e) {
            //if there is an exception
            //you may not want to out
            //right throw it out
            $response->setStatus(500, '500 Server Error');
            $continue = $this->getErrorProcessor()->process($request, $response, $e);
            //if there's an error in the error processor then let it be thrown
        }
        
        return $continue;
    }
}
