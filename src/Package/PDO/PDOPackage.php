<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Framework\Package\PDO;

use PDO;

use Cradle\Package\Package;
use Cradle\Framework\FrameworkHandler;

/**
 * Config Package
 *
 * @vendor   Cradle
 * @package  Package
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class PDOPackage
{
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
   * Mutates to PDO using the given config
   *
   * @param *array $config
   *
   * @return Package
   */
  public function loadConfig(array $config): Package
  {
    $options = [];
    if (isset($config['options']) && is_array($config['options'])) {
      $options = $config['options'];
    }

    $host = $port = $name = $user = $pass = '';
    if (isset($config['host']) && $config['host']) {
      $host = sprintf('host=%s;', $config['host']);
    }

    if (isset($config['port']) && $config['port']) {
      $port = sprintf('port=%s;', $config['port']);
    }

    if (isset($config['name']) && $config['name']) {
      $name = sprintf('dbname=%s;', $config['name']);
    }

    if (isset($config['user']) && $config['user']) {
      $user = $config['user'];
    }

    if (isset($config['pass']) && $config['pass']) {
      $pass = $config['pass'];
    }

    $connection = sprintf('mysql:%s%s%s', $host, $port, $name);

    $resource = $this->handler->package('resolver')->resolve(
      PDO::class,
      $connection,
      $user,
      $pass,
      $options
    );

    return $this->loadPDO($resource);
  }

  /**
   * Mutates to PDO using the given config
   *
   * @param *PDO $resource
   *
   * @return Package
   */
  public function loadPDO(PDO $resource): Package
  {
    $package = $this->handler->package('pdo');
    $package->mapPackageMethods($resource);
    return $package;
  }
}
