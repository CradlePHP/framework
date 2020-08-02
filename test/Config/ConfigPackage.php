<?php

namespace Cradle\Framework\Config;

use StdClass;
use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 13:49:45.
 */
class Cradle_Framework_Config_ConfigPackage_Test extends TestCase
{
  /**
   * @var ConfigPackage
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp()
  {
    $this->object = new ConfigPackage;
    $this->object->setFolder(dirname(__DIR__) . '/assets/config');
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown()
  {
  }

  /**
   * @covers Cradle\Framework\Config\ConfigPackage::set
   * @covers Cradle\Framework\Config\ConfigPackage::get
   * @covers Cradle\Framework\Config\ConfigPackage::exists
   */
  public function testSet()
  {
    $this->object->set('foo/bar/zoo', [
      'foo' => [
        'bar' => 'zoo',
        'zoo' => 'foo'
      ]
    ]);

    $this->assertTrue(file_exists(dirname(__DIR__) . '/assets/config/foo/bar/zoo.php'));
    $this->assertEquals('zoo', $this->object->get('foo/bar/zoo')['foo']['bar']);
    $this->assertEquals('zoo', $this->object->get('foo/bar/zoo', 'foo', 'bar'));

    $this->object->set('foo/bar/zoo', 'bar', [
      'foo' => [
        'bar' => 'zoo',
        'zoo' => 'foo'
      ]
    ]);

    $this->assertEquals('zoo', $this->object->get('foo/bar/zoo')['bar']['foo']['bar']);
    $this->assertEquals('zoo', $this->object->get('foo/bar/zoo', 'bar', 'foo', 'bar'));
  }
}
