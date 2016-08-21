<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Framework\Flow;

use Cradle\Profiler\LoggerTrait;
use Cradle\Helper\SingletonTrait;

/**
 * Factory for log related flows
 *
 * @vendor   Cradle
 * @package  Framework
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Log
{
    use LoggerTrait, SingletonTrait;

    /**
     * Reports something during the flow
     *
     * @param *string $message
     *
     * @return Closure
     */
    public function report($message)
    {
        $logger = static::i();
        return function () use ($logger, $message) {
            $logger->log($message);
        };
    }

    /**
     * Rawly outputs a message during the flow
     *
     * @param *string $message
     * @param bool    $quit
     *
     * @return Closure
     */
    public function debug($message, $quit = false)
    {
        return function () use ($message, $quit) {
            echo $message;
            // @codeCoverageIgnoreStart
            if ($quit) {
                exit;
            }
            // @codeCoverageIgnoreEnd
        };
    }

    /**
     * Register a reporting tool
     *
     * @param *callable $logger
     */
    public static function register($logger)
    {
        static::i()->addLogger($logger);
    }
}
