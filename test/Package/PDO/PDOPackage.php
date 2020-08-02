<?php

namespace Cradle\Framework\Package\PDO;

use PHPUnit\Framework\TestCase;

use StdClass;
use PDO;

use Cradle\Framework\FrameworkHandler;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 13:49:45.
 */
class Cradle_Framework_PDO_PDOPackage_Test extends TestCase
{
  /**
   * @var FrameworkHandler
   */
  protected $framework;

  /**
   * @var PDOPackage
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp()
  {
    $this->framework = new FrameworkHandler;
    $this->object = new PDOPackage($this->framework);
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown()
  {
  }

  /**
   * @covers Cradle\Framework\Package\PDO\PDOPackage::loadConfig
   * @covers Cradle\Framework\Package\PDO\PDOPackage::loadPDO
   */
  public function testLoad()
  {
    $actual = $this->framework->package('pdo')->getPackageMap();
    $this->assertInstanceOf(PDOPackage::class, $actual);

    $this->framework->package('pdo')->loadConfig([
      'type' => 'mysql',
      'host' => '127.0.0.1',
      'port' => '3306',
      'name' => 'testing_db',
      'user' => 'root',
      'pass' => ''
    ]);

    $actual = $this->framework->package('pdo')->getPackageMap();
    $this->assertInstanceOf(PDO::class, $actual);
  }
}
