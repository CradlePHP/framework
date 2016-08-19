<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Http;

use Cradle\Event\EventTrait;

use Cradle\Helper\InstanceTrait;
use Cradle\Helper\LoopTrait;
use Cradle\Helper\ConditionalTrait;

use Cradle\Profiler\InspectorTrait;
use Cradle\Profiler\LoggerTrait;

use Cradle\Resolver\StateTrait;

/**
 * Main HTTP Handler which connects everything together.
 * We moved out everything that is not the main process flow
 * to traits and reinserted them here to make this easy to follow.
 *
 * @vendor   Cradle
 * @package  Http
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class HttpHandler
{
    use HttpTrait,
        EventTrait,
        InstanceTrait,
        LoopTrait,
        ConditionalTrait,
        InspectorTrait,
        LoggerTrait,
        StateTrait
        {
            StateTrait::__callResolver as __call;
        }

    /**
     * @const STATUS_404 Status template
     */
    const STATUS_404 = '404 Not Found';

    /**
     * @const STATUS_500 Status template
     */
    const STATUS_500 = '500 Server Error';
}
