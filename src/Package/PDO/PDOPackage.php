<?php //-->
/**
 * This file is part of the Cradle PHP Project.
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\Framework\Package\PDO;

use PDO;

use Cradle\Package\Package;
use Cradle\Framework\FrameworkHandler;

/**
 * PDO Package
 *
 * @vendor   Cradle
 * @package  Package
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
    $host = $port = $name = $user = $pass = '';
    //if host
    if (isset($config['host']) && $config['host']) {
      //set host string
      $host = sprintf('host=%s;', $config['host']);
    }

    //if port
    if (isset($config['port']) && $config['port']) {
      //set port string
      $port = sprintf('port=%s;', $config['port']);
    }

    //if bane
    if (isset($config['name']) && $config['name']) {
      //set dbname string
      $name = sprintf('dbname=%s;', $config['name']);
    }

    //if user
    if (isset($config['user']) && $config['user']) {
      //set user string
      $user = $config['user'];
    }

    //if pass
    if (isset($config['pass']) && $config['pass']) {
      //set pass string
      $pass = $config['pass'];
    }

    $options = [];
    //if options
    if (isset($config['options']) && is_array($config['options'])) {
      //set options
      $options = $config['options'];
    }

    $type = 'mysql';
    if (isset($config['type']) && $config['type']) {
      $type = $config['type'];
    }

    //make a connection string
    $connection = sprintf('%s:%s%s%s', $type, $host, $port, $name);

    //get the resolver
    $resolver = $this->handler->package('resolver');

    //load the pdo
    return $this->loadPDO($resolver->resolve(
      PDO::class,
      $connection,
      $user,
      $pass,
      $options
    ));
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
    //get the PDO package
    $package = $this->handler->package('pdo');
    //set the resource
    $package->mapPackageMethods($resource);
    return $package;
  }
}
