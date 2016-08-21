<?php

namespace Cradle\Http\Request;

use PHPUnit_Framework_TestCase;
use Cradle\Data\Registry;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-28 at 11:36:34.
 */
class Cradle_Http_Request_GetTrait_Test extends PHPUnit_Framework_TestCase
{
    /**
     * @var GetTrait
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new GetTraitStub;
        
        $this->object->set('get', array(
            'foo' => 'bar',
            'bar' => 'foo'
        ));
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * covers Cradle\Http\Request\GetTrait::getGet
     */
    public function testGetGet()
    {
        $this->assertEquals('bar', $this->object->getGet('foo'));
    }

    /**
     * covers Cradle\Http\Request\GetTrait::hasGet
     */
    public function testHasGet()
    {
        $this->assertTrue($this->object->hasGet('foo'));
        $this->assertFalse($this->object->hasGet('zoo'));
    }

    /**
     * covers Cradle\Http\Request\GetTrait::removeGet
     */
    public function testRemoveGet()
    {
        $this->object->removeGet('foo');
        $this->assertFalse($this->object->hasGet('foo'));
    }

    /**
     * covers Cradle\Http\Request\GetTrait::setGet
     */
    public function testSetGet()
    {
        $instance = $this->object->setGet(array(
            'foo' => 'bar',
            'bar' => 'foo'
        ));
        
        $this->assertInstanceOf('Cradle\Http\Request\GetTraitStub', $instance);
		
		$instance = $this->object->setGet('zoo');
        $this->assertInstanceOf('Cradle\Http\Request\GetTraitStub', $instance);

        $instance = $this->object->setGet('zoo', 'foo', 'bar');
        $this->assertInstanceOf('Cradle\Http\Request\GetTraitStub', $instance);
    }
}

if(!class_exists('Cradle\Http\Request\GetTraitStub')) {
    class GetTraitStub extends Registry
    {
        use GetTrait;
    }
}