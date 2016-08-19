<?php

namespace Cradle\Data;

use PHPUnit_Framework_TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 02:10:59.
 */
class Cradle_Data_DataTrait_Test extends PHPUnit_Framework_TestCase
{
    /**
     * @var DataTrait
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new DataTraitStub;
		
		$this->object->setDot('foo', 'bar');
		$this->object->setDot('bar', 'foo');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Cradle\Data\DataTrait::offsetExists
     */
    public function testOffsetExists()
    {
        $this->assertTrue($this->object->offsetExists('foo'));
        $this->assertFalse($this->object->offsetExists(3));
    }

    /**
     * @covers Cradle\Data\DataTrait::offsetGet
     */
    public function testOffsetGet()
    {
        $actual = $this->object->offsetGet('foo');
		$this->assertEquals('bar', $actual);
    }

    /**
     * @covers Cradle\Data\DataTrait::offsetSet
     */
    public function testOffsetSet()
    {
        $this->object->offsetSet('zoo', 2);
		
		$this->assertEquals(2, $this->object->offsetGet('zoo'));
    }

    /**
     * @covers Cradle\Data\DataTrait::offsetUnset
     */
    public function testOffsetUnset()
    {
		$this->object->offsetUnset('foo');
		$this->assertNull($this->object->offsetGet('foo'));
    }

    /**
     * @covers Cradle\Data\DataTrait::current
     */
    public function testCurrent()
    {
        $actual = $this->object->current();
    	$this->assertEquals('bar', $actual);
    }

    /**
     * @covers Cradle\Data\DataTrait::key
     */
    public function testKey()
    {
        $actual = $this->object->key();
    	$this->assertEquals('foo', $actual);
    }

    /**
     * @covers Cradle\Data\DataTrait::next
     */
    public function testNext()
    {
		$this->object->next();
        $actual = $this->object->current();
    	$this->assertEquals('foo', $actual);
    }

    /**
     * @covers Cradle\Data\DataTrait::rewind
     */
    public function testRewind()
    {
		$this->object->rewind();
        $actual = $this->object->current();
    	$this->assertEquals('bar', $actual);
    }

    /**
     * @covers Cradle\Data\DataTrait::valid
     */
    public function testValid()
    {
        $this->assertTrue($this->object->valid());
    }

    /**
     * @covers Cradle\Data\DataTrait::count
     */
    public function testCount()
    {
        $this->assertEquals(2, $this->object->count());
    }

    /**
     * @covers Cradle\Data\DataTrait::getDot
     */
    public function testGetDot()
    {
        $this->assertEquals('bar', $this->object->getDot('foo'));
    }

    /**
     * @covers Cradle\Data\DataTrait::isDot
     */
    public function testIsDot()
    {
		$this->assertTrue($this->object->isDot('bar'));
    }

    /**
     * @covers Cradle\Data\DataTrait::removeDot
     */
    public function testRemoveDot()
    {
		$this->object->removeDot('foo');
		$this->assertFalse($this->object->isDot('foo'));
    }

    /**
     * @covers Cradle\Data\DataTrait::setDot
     */
    public function testSetDot()
    {
		$this->object->setDot('zoo', 2);
        $this->assertEquals(2, $this->object->getDot('zoo'));
    }

    /**
     * @covers Cradle\Data\DataTrait::__callData
     */
    public function test__callData()
    {
        $instance = $this->object->__callData('setZoo', array(2));
		$this->assertInstanceOf('Cradle\Data\DataTraitStub', $instance);
		
        $actual = $this->object->__callData('getZoo', array());
		
		$this->assertEquals(2, $actual);
    }

    /**
     * @covers Cradle\Data\DataTrait::__getData
     */
    public function test__getData()
    {
        $actual = $this->object->__getData('foo');
		$this->assertEquals('bar', $actual);
    }

    /**
     * @covers Cradle\Data\DataTrait::__setData
     */
    public function test__setData()
    {
        $this->object->__setData('zoo', 2);
        $actual = $this->object->__getData('zoo');
		
		$this->assertEquals(2, $actual);
    }

    /**
     * @covers Cradle\Data\DataTrait::__toStringData
     */
    public function test__toStringData()
    {
		$this->assertEquals(json_encode([
			'foo' => 'bar',
			'bar' => 'foo'
		], JSON_PRETTY_PRINT), $this->object->__toStringData());
    }

    /**
     * @covers Cradle\Data\DataTrait::generator
     */
    public function testGenerator()
    {
        foreach($this->object->generator() as $i => $value);
		
		$this->assertEquals('bar', $i);
    }
}

if(!class_exists('Cradle\Data\DataTraitStub')) {
	class DataTraitStub
	{
		use DataTrait;
	}
}
