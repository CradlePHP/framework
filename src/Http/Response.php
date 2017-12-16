<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Http;

use Cradle\Data\Registry;

use Cradle\Helper\InstanceTrait;
use Cradle\Helper\LoopTrait;
use Cradle\Helper\ConditionalTrait;

use Cradle\Profiler\InspectorTrait;
use Cradle\Profiler\LoggerTrait;

use Cradle\Resolver\StateTrait;

use Cradle\Http\Response\ResponseInterface;

use Cradle\Http\Response\ContentTrait;
use Cradle\Http\Response\HeaderTrait;
use Cradle\Http\Response\PageTrait;
use Cradle\Http\Response\RestTrait;
use Cradle\Http\Response\StatusTrait;

/**
 * Http Response Object
 *
 * @vendor   Cradle
 * @package  Server
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Response extends Registry implements ResponseInterface
{
    use ContentTrait,
        HeaderTrait,
        PageTrait,
        RestTrait,
        StatusTrait,
        InstanceTrait,
        LoopTrait,
        ConditionalTrait,
        InspectorTrait,
        LoggerTrait,
        StateTrait;

    /**
     * Loads default data
     *
     * @return Response
     */
    public function load(): Response
    {
        $this
            ->addHeader('Content-Type', 'text/html; charset=utf-8')
            ->setStatus(200, '200 OK');

        return $this;
    }
}
