<?php

namespace Cradle\Framework;

use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 13:49:45.
 */
class Cradle_Framework_Package_Test extends TestCase
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
        $this->object = new Package('foo/bar');
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

    /**
     * @covers Cradle\Framework\Package::__construct
     * @covers Cradle\Framework\Package::getPackagePath
     */
    public function testGetPackagePath()
    {
        //foo/bar
        $actual = $this->object->getPackagePath();
        $this->assertContains('/vendor/foo/bar', $actual);

        $this->object->__construct('/foo/bar');
        $actual = $this->object->getPackagePath();
        $this->assertContains('/foo/bar', $actual);
        $this->assertFalse(strpos($actual, '/vendor/foo/bar'));

        $this->object->__construct('foo');
        $actual = $this->object->getPackagePath();
        $this->assertFalse($actual);
    }

    /**
     * @covers Cradle\Framework\Package::getPackageRoot
     */
    public function testGetPackageRoot()
    {
        //foo/bar
        $actual = $this->object->getPackageRoot();
        $this->assertContains('/vendor', $actual);

        $this->object->__construct('/foo/bar');
        $actual = $this->object->getPackageRoot();
        $this->assertFalse(strpos($actual, '/vendor'));

        $this->object->__construct('foo');
        $actual = $this->object->getPackageRoot();
        $this->assertFalse($actual);
    }

    /**
     * @covers Cradle\Framework\Package::getPackageType
     */
    public function testGetPackageType()
    {
        //foo/bar
        $actual = $this->object->getPackageType();
        $this->assertEquals('vendor', $actual);

        $this->object->__construct('/foo/bar');
        $actual = $this->object->getPackageType();
        $this->assertEquals('root', $actual);

        $this->object->__construct('foo');
        $actual = $this->object->getPackageType();
        $this->assertEquals('pseudo', $actual);
    }

    /**
     * @covers Cradle\Framework\Package::getPackageVersion
     */
    public function testGetPackageVersion()
    {
        $this->object->__construct('foo');
        $actual = $this->object->getPackageVersion();
        $this->assertEquals('0.0.0', $actual);
    }
}