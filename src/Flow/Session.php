<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Framework\Flow;

/**
 * Factory for session related flows
 *
 * @vendor   Cradle
 * @package  Framework
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Session
{
    /**
     * Sets up an error flash message
     *
     * @param *string $message
     *
     * @return Closure
     */
    public function error($message)
    {
        return $this->flash($message, 'error');
    }

    /**
     * Sets up a flash message
     *
     * @param string|null $message
     * @param string      $type
     *
     * @return Closure
     */
    public function flash($message = null, $type = 'info')
    {
        return function ($request, $response) use ($message, $type) {
            $flash = array(
                'type' => $type,
                'message' => $message
            );

            //if no message was passed
            if (is_null($flash['message'])) {
                //get it from the response
                $flash['message'] = $response->getMessage();
                if ($response->isError()) {
                    $flash['type'] = 'error';
                } else if ($response->isSuccess()) {
                    $flash['type'] = 'success';
                }
            }

            //because we could be in CLI mode
            if (isset($_SESSION)) {
                $_SESSION['flash'] = $flash;
            }

            $response->set('page', 'flash', $flash);

            $error = null;
            if ($type === 'error') {
                $error = true;
            } else if ($type === 'success') {
                $error = false;
            }

            $response->setError($error, $message);
        };
    }

    /**
     * Sets up an info flash message
     *
     * @param *string $message
     *
     * @return Closure
     */
    public function info($message)
    {
        return $this->flash($message, 'info');
    }

    /**
     * Sets up a success flash message
     *
     * @param *string $message
     *
     * @return Closure
     */
    public function success($message)
    {
        return $this->flash($message, 'success');
    }

    /**
     * Calls a redirect given url
     *
     * @param *string $url
     *
     * @return Closure
     */
    public function redirectTo($url)
    {
        return function () use ($url) {
            if (strpos($url, '://') === false
                && strpos($url, '/') !== 0
            )
            {
                $url = '/' . $url;
            }

            $this->getDispatcher()->redirect($url);
        };
    }
}
