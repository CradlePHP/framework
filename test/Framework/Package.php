<?php

namespace Cradle\Framework;

use PHPUnit_Framework_TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 13:49:45.
 */
class Cradle_Framework_Package_Test extends PHPUnit_Framework_TestCase
{
    /**
     * @var Package
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Package;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Cradle\Framework\Package::__call
     */
    public function test__call()
    {
        $this->object->addMethod('foo', function() {
			return 'bar';
		});

		$actual = $this->object->__call('foo', array());
		$this->assertEquals('bar', $actual);

        $trigger = false;
        try {
            $actual = $this->object->__call('bar', array());
        } catch(Exception $e) {
            $trigger = true;
        }

        $this->assertTrue($trigger);
    }

    /**
     * @covers Cradle\Framework\Package::addMethod
     */
    public function testAddMethod()
    {
        $this->object->addMethod('foo', function() {
			return 'bar';
		});

		$actual = $this->object->__call('foo', array());
		$this->assertEquals('bar', $actual);
    }
}