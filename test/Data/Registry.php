<?php

namespace Cradle\Data;

use StdClass;
use PHPUnit_Framework_TestCase;
use Cradle\Data\Registry\RegistryException;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 02:11:00.
 */
class Cradle_Data_Registry_Test extends PHPUnit_Framework_TestCase
{
    /**
     * @var Registry
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Registry([
            'post_id' => 1,
            'post_title' => 'Foobar 1',
            'post_detail' => 'foobar 1',
            'post_active' => 1,
            'post_flag' => false
        ]);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Cradle\Data\Registry::__call
     */
    public function test__call()
    {
        $instance = $this->object->__call('setPostTitle', array('Foobar 4'));
        $this->assertInstanceOf('Cradle\Data\Registry', $instance);

        $actual = $this->object->__call('getPostTitle', array());

        $this->assertEquals('Foobar 4', $actual);

        $instance = $this->object->__call(Collection::class, array());
        $this->assertInstanceOf('Cradle\Data\Collection', $instance);

        $thrown = false;
        try {
            $this->object->__call('foobar', array());
        } catch(RegistryException $e) {
            $thrown = true;
        }

        $this->assertTrue($thrown);
    }

    /**
     * @covers Cradle\Data\Registry::exists
     */
    public function testExists()
    {
        $this->assertTrue($this->object->exists('post_title'));
        $this->assertFalse($this->object->exists('post_created'));

        $this->assertTrue($this->object->exists());
    }

    /**
     * @covers Cradle\Data\Registry::get
     */
    public function testGet()
    {
        $data = $this->object->get();
        $this->assertEquals('Foobar 1', $this->object->get('post_title'));
        $this->assertEquals('Foobar 1', $data['post_title']);
    }

    /**
     * @covers Cradle\Data\Registry::isEmpty
     */
    public function testIsEmpty()
    {
        $this->assertTrue($this->object->isEmpty('post_created'));
        $this->assertFalse($this->object->isEmpty('post_title'));
        $this->assertFalse($this->object->isEmpty());
        $this->assertFalse($this->object->isEmpty('post_flag'));
    }

    /**
     * @covers Cradle\Data\Registry::remove
     */
    public function testRemove()
    {
        $this->object->remove('post_id');
        $this->assertFalse($this->object->exists('post_id'));

        $instance = $this->object->remove();
        $this->assertInstanceOf('Cradle\Data\Registry', $instance);
    }

    /**
     * @covers Cradle\Data\Registry::set
     */
    public function testSet()
    {
        $this->object->set('post_id', 2);
        $this->assertEquals(2, $this->object->get('post_id'));

        $instance = $this->object->set([
            'post_id' => 1,
            'post_title' => 'Foobar 1',
            'post_detail' => 'foobar 1',
            'post_active' => 1,
            'post_flag' => false
        ]);

        $this->assertInstanceOf('Cradle\Data\Registry', $instance);

        $instance = $this->object->set();

        $this->assertInstanceOf('Cradle\Data\Registry', $instance);
    }

    /**
     * @covers Cradle\Data\Registry::offsetExists
     */
    public function testOffsetExists()
    {
        $this->assertTrue($this->object->offsetExists('post_id'));
        $this->assertFalse($this->object->offsetExists(3));
    }

    /**
     * @covers Cradle\Data\Registry::offsetGet
     */
    public function testOffsetGet()
    {
        $actual = $this->object->offsetGet('post_id');
        $this->assertEquals(1, $actual);
    }

    /**
     * @covers Cradle\Data\Registry::offsetSet
     */
    public function testOffsetSet()
    {
        $this->object->offsetSet('post_id', 2);

        $this->assertEquals(2, $this->object['post_id']);
    }

    /**
     * @covers Cradle\Data\Registry::offsetUnset
     */
    public function testOffsetUnset()
    {
        $this->object->offsetUnset('post_id');
        $this->assertFalse(isset($this->object['post_id']));
    }

    /**
     * @covers Cradle\Data\Registry::current
     */
    public function testCurrent()
    {
        $actual = $this->object->current();
        $this->assertEquals(1, $actual);
    }

    /**
     * @covers Cradle\Data\Registry::key
     */
    public function testKey()
    {
        $actual = $this->object->key();
        $this->assertEquals('post_id', $actual);
    }

    /**
     * @covers Cradle\Data\Registry::next
     */
    public function testNext()
    {
        $this->object->next();
        $actual = $this->object->current();
        $this->assertEquals('Foobar 1', $actual);
    }

    /**
     * @covers Cradle\Data\Registry::rewind
     */
    public function testRewind()
    {
        $this->object->rewind();
        $actual = $this->object->current();
        $this->assertEquals(1, $actual);
    }

    /**
     * @covers Cradle\Data\Registry::valid
     */
    public function testValid()
    {
        $this->assertTrue($this->object->valid());
    }

    /**
     * @covers Cradle\Data\Registry::count
     */
    public function testCount()
    {
        $this->assertEquals(5, count($this->object));
    }

    /**
     * @covers Cradle\Data\Registry::getDot
     */
    public function testGetDot()
    {
        $this->assertEquals(1, $this->object->getDot('post_id'));
    }

    /**
     * @covers Cradle\Data\Registry::isDot
     */
    public function testIsDot()
    {
        $this->assertTrue($this->object->isDot('post_id'));
    }

    /**
     * @covers Cradle\Data\Registry::removeDot
     */
    public function testRemoveDot()
    {
        $this->object->removeDot('post_id');
        $this->assertFalse($this->object->isDot('post_id'));
    }

    /**
     * @covers Cradle\Data\Registry::setDot
     */
    public function testSetDot()
    {
        $this->object->setDot('post_id', 2);
        $this->assertEquals(2, $this->object->getDot('post_id'));
    }

    /**
     * @covers Cradle\Data\Registry::__callData
     */
    public function test__callData()
    {
        $instance = $this->object->__call('setPostTitle', array('Foobar 4'));
        $this->assertInstanceOf('Cradle\Data\Registry', $instance);

        $actual = $this->object->__call('getPostTitle', array());

        $this->assertEquals('Foobar 4', $actual);
    }

    /**
     * @covers Cradle\Data\Registry::__get
     */
    public function test__get()
    {
        $actual = $this->object->post_title;
        $this->assertEquals('Foobar 1', $actual);
    }

    /**
     * @covers Cradle\Data\Registry::__getData
     */
    public function test__getData()
    {
        $actual = $this->object->post_title;
        $this->assertEquals('Foobar 1', $actual);
    }

    /**
     * @covers Cradle\Data\Registry::__set
     */
    public function test__set()
    {
        $this->object->post_title = 'Foobar 4';
        $actual = $this->object->post_title;

        $this->assertEquals('Foobar 4', $actual);
    }

    /**
     * @covers Cradle\Data\Registry::__setData
     */
    public function test__setData()
    {
        $this->object->post_title = 'Foobar 4';
        $actual = $this->object->post_title;

        $this->assertEquals('Foobar 4', $actual);
    }

    /**
     * @covers Cradle\Data\Registry::__toString
     */
    public function test__toString()
    {
        $this->assertEquals(json_encode([
            'post_id' => 1,
            'post_title' => 'Foobar 1',
            'post_detail' => 'foobar 1',
            'post_active' => 1,
            'post_flag' => false
        ], JSON_PRETTY_PRINT), (string) $this->object);
    }

    /**
     * @covers Cradle\Data\Registry::__toStringData
     */
    public function test__toStringData()
    {
        $this->assertEquals(json_encode([
            'post_id' => 1,
            'post_title' => 'Foobar 1',
            'post_detail' => 'foobar 1',
            'post_active' => 1,
            'post_flag' => false
        ], JSON_PRETTY_PRINT), (string) $this->object);
    }

    /**
     * @covers Cradle\Data\Registry::generator
     */
    public function testGenerator()
    {
        foreach($this->object->generator() as $i => $value);

        $this->assertEquals('post_flag', $i);
    }

    /**
     * @covers Cradle\Data\Registry::getEventHandler
     */
    public function testGetEventHandler()
    {
        $instance = $this->object->getEventHandler();
        $this->assertInstanceOf('Cradle\Event\EventHandler', $instance);
    }

    /**
     * @covers Cradle\Data\Registry::on
     */
    public function testOn()
    {
        $trigger = new StdClass();
        $trigger->success = null;

        $callback = function() use ($trigger) {
            $trigger->success = true;
        };

        $instance = $this
            ->object
            ->on('foobar', $callback)
            ->trigger('foobar');

        $this->assertInstanceOf('Cradle\Data\Registry', $instance);
        $this->assertTrue($trigger->success);
    }

    /**
     * @covers Cradle\Data\Registry::setEventHandler
     */
    public function testSetEventHandler()
    {
        $instance = $this->object->setEventHandler(new \Cradle\Event\EventHandler);
        $this->assertInstanceOf('Cradle\Data\Registry', $instance);
    }

    /**
     * @covers Cradle\Data\Registry::trigger
     */
    public function testTrigger()
    {
        $trigger = new StdClass();
        $trigger->success = null;

        $callback = function() use ($trigger) {
            $trigger->success = true;
        };

        $instance = $this
            ->object
            ->on('foobar', $callback)
            ->trigger('foobar');

        $this->assertInstanceOf('Cradle\Data\Registry', $instance);
        $this->assertTrue($trigger->success);
    }

    /**
     * @covers Cradle\Data\Registry::i
     */
    public function testI()
    {
        $instance1 = Registry::i();
        $this->assertInstanceOf('Cradle\Data\Registry', $instance1);

        $instance2 = Registry::i();
        $this->assertTrue($instance1 !== $instance2);
    }

    /**
     * @covers Cradle\Data\Registry::loop
     */
    public function testLoop()
    {
        $self = $this;
        $this->object->loop(function($i) use ($self) {
            $self->assertInstanceOf('Cradle\Data\Registry', $this);

            if ($i == 2) {
                return false;
            }
        });
    }

    /**
     * @covers Cradle\Data\Registry::when
     */
    public function testWhen()
    {
        $self = $this;
        $test = 'Good';
        $this->object->when(function() use ($self) {
            $self->assertInstanceOf('Cradle\Data\Registry', $this);
            return false;
        }, function() use ($self, &$test) {
            $self->assertInstanceOf('Cradle\Data\Registry', $this);
            $test = 'Bad';
        });

        $this->assertSame('Good', $test);
    }

    /**
     * @covers Cradle\Data\Registry::getInspectorHandler
     */
    public function testGetInspectorHandler()
    {
        $instance = $this->object->getInspectorHandler();
        $this->assertInstanceOf('Cradle\Profiler\InspectorInterface', $instance);
    }

    /**
     * @covers Cradle\Data\Registry::inspect
     */
    public function testInspect()
    {
        ob_start();
        $this->object->inspect('foobar');
        $contents = ob_get_contents();
        ob_end_clean();

        $this->assertEquals(
            '<pre>INSPECTING Variable:</pre><pre>foobar</pre>',
            $contents
        );
    }

    /**
     * @covers Cradle\Data\Registry::setInspectorHandler
     */
    public function testSetInspectorHandler()
    {
        $instance = $this->object->setInspectorHandler(new \Cradle\Profiler\InspectorHandler);
        $this->assertInstanceOf('Cradle\Data\Registry', $instance);
    }

    /**
     * @covers Cradle\Data\Registry::addLogger
     */
    public function testAddLogger()
    {
        $instance = $this->object->addLogger(function() {});
        $this->assertInstanceOf('Cradle\Data\Registry', $instance);
    }

    /**
     * @covers Cradle\Data\Registry::log
     */
    public function testLog()
    {
        $trigger = new StdClass();
        $trigger->success = null;
        $this->object->addLogger(function($trigger) {
            $trigger->success = true;
        })
        ->log($trigger);


        $this->assertTrue($trigger->success);
    }

    /**
     * @covers Cradle\Data\Registry::loadState
     */
    public function testLoadState()
    {
        $state1 = new Registry(array());
        $state2 = new Registry(array());

        $state1->saveState('state1');
        $state2->saveState('state2');

        $this->assertTrue($state2 === $state1->loadState('state2'));
        $this->assertTrue($state1 === $state2->loadState('state1'));
    }

    /**
     * @covers Cradle\Data\Registry::saveState
     */
    public function testSaveState()
    {
        $state1 = new Registry(array());
        $state2 = new Registry(array());

        $state1->saveState('state1');
        $state2->saveState('state2');

        $this->assertTrue($state2 === $state1->loadState('state2'));
        $this->assertTrue($state1 === $state2->loadState('state1'));
    }

    /**
     * @covers Cradle\Data\Registry::__callResolver
     */
    public function test__callResolver()
    {
        $actual = $this->object->addResolver(Registry::class, function() {});
        $this->assertInstanceOf('Cradle\Data\Registry', $actual);
    }

    /**
     * @covers Cradle\Data\Registry::addResolver
     */
    public function testAddResolver()
    {
        $actual = $this->object->addResolver(Registry::class, function() {});
        $this->assertInstanceOf('Cradle\Data\Registry', $actual);
    }

    /**
     * @covers Cradle\Data\Registry::getResolverHandler
     */
    public function testGetResolverHandler()
    {
        $actual = $this->object->getResolverHandler();
        $this->assertInstanceOf('Cradle\Resolver\ResolverInterface', $actual);
    }

    /**
     * @covers Cradle\Data\Registry::resolve
     */
    public function testResolve()
    {
        $actual = $this->object->addResolver(
            ResolverCallStub::class,
            function() {
                return new ResolverAddStub();
            }
        )
        ->resolve(ResolverCallStub::class)
        ->foo('bar');

        $this->assertEquals('barfoo', $actual);
    }

    /**
     * @covers Cradle\Data\Registry::resolveShared
     */
    public function testResolveShared()
    {
        $actual = $this
            ->object
            ->resolveShared(ResolverSharedStub::class)
            ->reset()
            ->foo('bar');

        $this->assertEquals('barfoo', $actual);

        $actual = $this
            ->object
            ->resolveShared(ResolverSharedStub::class)
            ->foo('bar');

        $this->assertEquals('barbar', $actual);
    }

    /**
     * @covers Cradle\Data\Registry::resolveStatic
     */
    public function testResolveStatic()
    {
        $actual = $this
            ->object
            ->resolveStatic(
                ResolverStaticStub::class,
                'foo',
                'bar'
            );

        $this->assertEquals('barfoo', $actual);
    }

    /**
     * @covers Cradle\Data\Registry::setResolverHandler
     */
    public function testSetResolverHandler()
    {
        $actual = $this->object->setResolverHandler(new \Cradle\Resolver\ResolverHandler);
        $this->assertInstanceOf('Cradle\Data\Registry', $actual);
    }
}

if(!class_exists('Cradle\Data\ResolverCallStub')) {
    class ResolverCallStub
    {
        public function foo($string)
        {
            return $string . 'foo';
        }
    }
}

if(!class_exists('Cradle\Data\ResolverAddStub')) {
    class ResolverAddStub
    {
        public function foo($string)
        {
            return $string . 'foo';
        }
    }
}

if(!class_exists('Cradle\Data\ResolverSharedStub')) {
    class ResolverSharedStub
    {
        public $name = 'foo';

        public function foo($string)
        {
            $name = $this->name;
            $this->name = $string;
            return $string . $name;
        }

        public function reset()
        {
            $this->name = 'foo';
            return $this;
        }
    }
}

if(!class_exists('Cradle\Data\ResolverStaticStub')) {
    class ResolverStaticStub
    {
        public static function foo($string)
        {
            return $string . 'foo';
        }
    }
}