<?php

namespace Cradle\Framework\Flow;

use PHPUnit_Framework_TestCase;
use Cradle\Framework\Flow;
use Cradle\Framework\App;
use Cradle\Http\Request;
use Cradle\Http\Response;
use Cradle\Http\HttpDispatcher;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 13:49:45.
 */
class Cradle_Framework_Flow_Session_Test extends PHPUnit_Framework_TestCase
{
    /**
     * @var Session
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Cradle\Framework\Flow\Session::error
     */
    public function testError()
    {
        $callback = Flow::session()->error('something');
        $this->assertInstanceOf('Closure', $callback);

        $request = new Request;
        $response = new Response;

        $actual = $callback($request, $response);
        $this->assertNull($actual);
    }

    /**
     * @covers Cradle\Framework\Flow\Session::flash
     */
    public function testFlash()
    {
        $callback = Flow::session()->flash('something');
        $this->assertInstanceOf('Closure', $callback);

        $request = new Request;
        $response = new Response;

        $actual = $callback($request, $response);
        $this->assertNull($actual);

        $callback = Flow::session()->flash('something', true);
        $this->assertInstanceOf('Closure', $callback);

        $request = new Request;
        $response = new Response;

        $actual = $callback($request, $response);
        $this->assertNull($actual);

        $callback = Flow::session()->flash('something', false);
        $this->assertInstanceOf('Closure', $callback);

        $request = new Request;
        $response = new Response;

        $actual = $callback($request, $response);
        $this->assertNull($actual);

        $callback = Flow::session()->flash();
        $this->assertInstanceOf('Closure', $callback);

        $request = new Request;
        $response = new Response;

        $actual = $callback($request, $response);
        $this->assertNull($actual);
    }

    /**
     * @covers Cradle\Framework\Flow\Session::info
     */
    public function testInfo()
    {
        $callback = Flow::session()->info('something');
        $this->assertInstanceOf('Closure', $callback);

        $request = new Request;
        $response = new Response;

        $actual = $callback($request, $response);
        $this->assertNull($actual);
    }

    /**
     * @covers Cradle\Framework\Flow\Session::success
     */
    public function testSuccess()
    {
        $callback = Flow::session()->success('something');
        $this->assertInstanceOf('Closure', $callback);

        $request = new Request;
        $response = new Response;

        $actual = $callback($request, $response);
        $this->assertNull($actual);
    }

    /**
     * @covers Cradle\Framework\Flow\Session::redirectTo
     */
    public function testRedirectTo()
    {
        $callback = Flow::session()->redirectTo('/foo/bar');
        $this->assertInstanceOf('Closure', $callback);

        $dispatcher = new HttpDispatcher(
            function() {},
            function() {}
        );

        $app = new App;
        $app->setDispatcher($dispatcher);

        $request = new Request;
        $response = new Response;

        $callback = $callback->bindTo($app);

        $actual = $callback($request, $response);
        $this->assertNull($actual);

        $actual = $callback($request, $response);
        $this->assertNull($actual);
    }
}