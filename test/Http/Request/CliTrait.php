<?php

namespace Cradle\Http\Request;

use PHPUnit_Framework_TestCase;
use Cradle\Data\Registry;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-28 at 11:36:34.
 */
class Cradle_Http_Request_CliTrait_Test extends PHPUnit_Framework_TestCase
{
    /**
     * @var CliTrait
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new CliTraitStub;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * covers Cradle\Http\Request\CliTrait::getArgs
     */
    public function testGetArgs()
    {
        $this->object->set('args', array(1, 2, 3));    
        $actual = $this->object->getArgs();
        $this->assertEquals(2, $actual[1]);
    }

    /**
     * covers Cradle\Http\Request\CliTrait::setArgs
     */
    public function testSetArgs()
    {
        $this->object->setArgs(array(1, 2, 3));    
        $actual = $this->object->getArgs();
        $this->assertEquals(2, $actual[1]);
    }
}

if(!class_exists('Cradle\Http\Request\CliTraitStub')) {
    class CliTraitStub extends Registry
    {
        use CliTrait;
    }
}
