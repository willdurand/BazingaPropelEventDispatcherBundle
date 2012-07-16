<?php

namespace Bazinga\Bundle\PropelEventDispatcherBundle\Tests;

use Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\Model\MyObject;

class BazingaPropelEventDispatcherBundleTest extends WebTestCase
{
    public function setUp()
    {
        $this->assertFalse(class_exists('Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\Model\MyObject', false));
        $this->assertFalse(class_exists('Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\Model\MyObject2', false));
    }

    public function testGetListener()
    {
        $listener = $this->getContainer()->get('listener.my_event_listener');

        $this->assertNotNull($listener);
        $this->assertInstanceOf('Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\EventListener\MyEventListener', $listener);
    }

    public function testGetListenerWithNonExistentClass()
    {
        $listener = $this->getContainer()->get('listener.my_event_listener_2');

        $this->assertNotNull($listener);
        $this->assertInstanceOf('Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\EventListener\MyEventListener', $listener);
    }

    public function testGetListenerWithNonExistentParentClass()
    {
        $listener = $this->getContainer()->get('listener.my_event_listener_3');
    }

    public function testFireEvent()
    {
        $object   = new MyObject();
        $listener = $this->getContainer()->get('listener.my_event_listener');

        $this->assertCount(0, $listener->getEvents());

        $object->preSave();
        $this->assertCount(1, $listener->getEvents());

        $events  = $listener->getEvents();
        $subject = $events[0]->getSubject();
        $this->assertSame($object, $subject);
    }
}
