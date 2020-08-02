<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Framework\Package\Event;

use Cradle\Event\EventTrait;

use Cradle\Helper\LoopTrait;
use Cradle\Helper\ConditionalTrait;

use Cradle\Profiler\InspectorTrait;
use Cradle\Profiler\LoggerTrait;

use Cradle\Resolver\StateTrait;

use Cradle\IO\Request;
use Cradle\IO\Response;

use Cradle\Framework\FrameworkHandler;

/**
 * Event Package
 *
 * @vendor   Cradle
 * @package  Package
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class EventPackage
{
  use EventTrait,
    LoopTrait,
    ConditionalTrait,
    InspectorTrait,
    LoggerTrait,
    StateTrait
    {
      EventTrait::on as onEvent;
  }

  /**
   * @var *PackageHandler $handler
   */
  protected $handler;

  /**
   * Add handler for scope when routing
   *
   * @param *PackageHandler $handler
   */
  public function __construct(FrameworkHandler $handler)
  {
    $this->handler = $handler;
  }

  /**
   * Runs an event like a method
   *
   * @param bool $load whether to load the RnRs
   *
   * @return array
   */
  public function method($event, $request = [], Response $response = null)
  {
    if (is_array($request)) {
      $request = Request::i()->load()->set('stage', [])->setStage($request);
    }

    if (!($request instanceof Request)) {
      $request = Request::i()->load();
    }

    if (is_null($response)) {
      $response = Response::i()->load();
    }

    $this->emit($event, $request, $response);

    if ($response->isError()) {
      return false;
    }

    return $response->getResults();
  }

  /**
   * Adds ... considerations
   *
   * @param *string          $event    The event name
   * @param *callable|string $callback The middleware handler
   * @param callable|string  ...$args  Arguments for flow
   *
   * @return EventPackage
   */
  public function on($event, callable $callback, ...$args): EventPackage
  {
    $emitter = $this->getEventEmitter();

    array_unshift($args, $callback);

    foreach ($args as $i => $callback) {
      $priority = 0;
      if (isset($args[$i + 1]) && is_numeric($args[$i + 1])) {
        $priority = $args[$i + 1];
      }

      //if it's a string
      if (is_string($callback)) {
        //it's an event
        $event = $callback;
        //make into callback
        $callback = function ($request, $response) use ($event) {
          $this->handler->package('event')->emit($event, $request, $response);
        };
      }

      //if it's closure
      if ($callback instanceof Closure) {
        //bind it
        $callback = $this->handler->bindCallback($callback);
      }

      //if it's callable
      if (is_callable($callback)) {
        //route it
        $emitter->on($event, $callback, $priority);
      }
    }

    return $this;
  }
}
