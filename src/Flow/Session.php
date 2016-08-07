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
    public static function error($message)
    {
        return self::flash($message, 'error');
    }

    /**
     * Sets up a flash message
     *
     * @param *string $message
     * @param string  $type
     *
     * @return Closure
     */
    public static function flash($message, $type = 'info')
    {
        return function($request, $response) use ($message, $type) {
            $flash = array(
                'type' => $type,
                'message' => $message
            );
            
            //because we could be in CLI mode
            if (isset($_SESSION)) {
                $_SESSION['flash'] = $flash;
            }

            $response->set('flash', $flash);
            
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
    public static function info($message)
    {
        return self::flash($message, 'info');
    }

    /**
     * Sets up a success flash message
     *
     * @param *string $message
     *
     * @return Closure
     */
    public static function success($message)
    {
        return self::flash($message, 'success');
    }

    /**
     * Calls a redirect given url
     *
     * @param *string $url
     *
     * @return Closure
     */
    public static function redirectTo($url)
    {
        return function() use ($url) {
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
