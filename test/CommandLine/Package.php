<?php

namespace Cradle\Framework\CommandLine;

use Cradle\Framework\CommandLine;
use StdClass;
use PHPUnit\Framework\TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 13:49:45.
 */
class Cradle_Framework_CommandLine_Package_Test extends TestCase
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
        $this->object = new Package(__DIR__);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Cradle\Framework\CommandLine\Package::__construct
     * @covers Cradle\Framework\CommandLine\Package::run
     */
    public function testRun()
    {
        $this->object = new Package(__DIR__);

        $test = new StdClass();
        $test->triggered = false;
        CommandLine::setMap(function ($output) use ($test) {
            $test->triggered = true;
        });

        $this->object->run(['package', 'foo/bar', 'run', 'bootstrap=../assets/bootstrap.php']);
        $this->assertTrue($test->triggered);

        $test->triggered = false;
        $this->object->run(['package', 'foo/bar', 'run', 'bootstrap=./../assets/bootstrap.php']);
        $this->assertTrue($test->triggered);

        $test->triggered = false;
        $this->object->run(['package', 'foo/bar', 'run', 'bootstrap='.__DIR__.'/../assets/bootstrap.php']);
        $this->assertTrue($test->triggered);

        $test->triggered = false;
        $this->object->run(['package', '/foo/bar', 'run', 'bootstrap=../assets/bootstrap.php']);
        $this->assertTrue($test->triggered);

        $test->triggered = false;
        $this->object->run(['package', 'foo/bar', 'run', 'bootstrap=../assets/bootstrap.php', '__query="foo=bar"']);
        $this->assertTrue($test->triggered);

        $test->triggered = false;
        $this->object->run(['package', 'foo/bar', 'run', 'bootstrap=../assets/bootstrap.php', '__json={"foo":"bar"}']);
        $this->assertTrue($test->triggered);

        $test->triggered = false;
        $this->object->run(['package', 'foo/bar', 'run', 'bootstrap=../assets/bootstrap.php', '__json64="'.base64_encode('{"foo":"bar"}').'"']);
        $this->assertTrue($test->triggered);
    }
}