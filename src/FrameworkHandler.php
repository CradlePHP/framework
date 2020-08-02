<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Framework;

use Cradle\Package\PackageHandler;

use Cradle\IO\IOTrait;
use Cradle\IO\Request;
use Cradle\IO\Response;

use Cradle\Event\EventTrait;

use Cradle\Helper\InstanceTrait;
use Cradle\Helper\LoopTrait;
use Cradle\Helper\ConditionalTrait;

use Cradle\Profiler\InspectorTrait;
use Cradle\Profiler\LoggerTrait;

use Cradle\Resolver\StateTrait;

/**
 * Handler for micro framework calls. Combines both
 * Http handling and Event handling
 *
 * @vendor   Cradle
 * @package  Package
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class FrameworkHandler extends PackageHandler
{
  use IOTrait,
    EventTrait,
    InstanceTrait,
    LoopTrait,
    ConditionalTrait,
    InspectorTrait,
    LoggerTrait,
    StateTrait
    {
      StateTrait::__callResolver as __call;
      IOTrait::error as errorIO;
      IOTrait::preprocess as preprocessIO;
      IOTrait::postprocess as postprocessIO;
  }

  /**
   * @const STATUS_404 Status template
   */
  const STATUS_404 = '404 Not Found';

  /**
   * @const STATUS_500 Status template
   */
  const STATUS_500 = '500 Server Error';

  /**
   * @const string BOOTSTRAP_FILE the default bootstrap file name
   */
  const BOOTSTRAP_FILE = '.cradle';

  /**
   * Setups dispatcher and global package
   */
  public function __construct()
  {
    $this
      //register the resolver
      ->register('resolver', sprintf('%s/Package/Resolver', __DIR__))
      //register the event
      ->register('event', sprintf('%s/Package/Event', __DIR__))
      //register the config
      ->register('config', sprintf('%s/Package/Config', __DIR__))
      //register the http
      ->register('http', sprintf('%s/Package/Http', __DIR__))
      //register the terminal
      ->register('terminal', sprintf('%s/Package/Terminal', __DIR__))
      //register the PDO
      ->register('pdo', sprintf('%s/Package/PDO', __DIR__))
      //use one global resolver
      ->setResolverHandler($this('resolver')->getResolverHandler())
      //use one global event emitter
      ->setEventEmitter($this('event')->getEventEmitter());
  }

  /**
   * Adds error middleware but also sends to http and terminal
   *
   * @param *callable $callback The middleware handler
   *
   * @return ErrorProcessorTrait
   */
  public function error(callable $callback)
  {
    $this->errorIO($callback);

    if ($this->isPackage('http')) {
      $this->package('http')->error($callback);
    }

    if ($this->isPackage('terminal')) {
      $this->package('terminal')->error($callback);
    }

    return $this;
  }

  /**
   * Creates a new Request and Response
   *
   * @param bool $load whether to load the RnRs
   *
   * @return array
   */
  public function makePayload(bool $load = true): array
  {
    $request = Request::i();
    $response = Response::i();

    if ($load) {
      $request->load();
      $response->load();

      $stage = $this->getRequest()->getStage();

      if (is_array($stage)) {
        $request->setSoftStage($stage);
      }
    }

    return [
      'request' => $request,
      'response' => $response
    ];
  }

  /**
   * Adds pre middleware but also sends to http and terminal
   *
   * @param *callable $callback The middleware handler
   *
   * @return PreProcessorTrait
   */
  public function preprocess(callable $callback)
  {
    $this->preprocessIO($callback);

    if ($this->isPackage('http')) {
      $this->package('http')->preprocess($callback);
    }

    if ($this->isPackage('terminal')) {
      $this->package('terminal')->preprocess($callback);
    }

    return $this;
  }

  /**
   * Adds post middleware but also sends to http and terminal
   *
   * @param *callable $callback The middleware handler
   *
   * @return PostProcessorTrait
   */
  public function postprocess(callable $callback)
  {
    $this->postprocessIO($callback);

    if ($this->isPackage('http')) {
      $this->package('http')->postprocess($callback);
    }

    if ($this->isPackage('terminal')) {
      $this->package('terminal')->postprocess($callback);
    }

    return $this;
  }
}
