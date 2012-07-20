<?php

namespace Bazinga\Bundle\PropelEventDispatcherBundle\Tests;

use Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\Model\MyObject;
use Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\Model\MyObject3;

class BazingaPropelEventDispatcherBundleTest extends WebTestCase
{
    public function testGetListener()
    {
        $listener = $this->getContainer()->get('listener.my_event_listener');

        $this->assertNotNull($listener);
        $this->assertInstanceOf('Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\EventListener\MyEventListener', $listener);
    }

    public function testGetListenerWithNonExistentClass()
    {
        $this->assertFalse(class_exists('Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\Model\MyObject2', false));

        $listener = $this->getContainer()->get('listener.my_event_listener_2');

        $this->assertNotNull($listener);
        $this->assertInstanceOf('Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\EventListener\MyEventListener', $listener);
    }

    public function testGetListenerWithNonExistentParentClass()
    {
        $this->assertFalse(class_exists('Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\Model\MyObject2', false));

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

    public function testFireEventWithEarlyBoot()
    {
        $listener = $this->getContainer()->get('listener.my_event_listener_4');
        $object   = new MyObject3();

        $this->assertCount(0, $listener->getEvents());

        $object->preSave();
        $this->assertCount(1, $listener->getEvents());

        $events  = $listener->getEvents();
        $subject = $events[0]->getSubject();
        $this->assertSame($object, $subject);
    }

    public function testSubscriber()
    {
        $subscriber = $this->getContainer()->get('subscriber.my_subscriber_1');

        $this->assertNotNull($subscriber);
        $this->assertInstanceOf('Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\EventListener\MyEventSubscriber', $subscriber);
    }

    public function testFireEventWithSubscriber()
    {
        $object     = new MyObject3();
        $subscriber = $this->getContainer()->get('subscriber.my_subscriber_1');

        $this->assertCount(0, $subscriber->getEvents());

        $object->preInsert();
        $object->preSave();
        $this->assertCount(1, $subscriber->getEvents());

        $events  = $subscriber->getEvents();
        $subject = $events[0]->getSubject();
        $this->assertEquals('pre_insert', $subject->source);
        $this->assertSame($object, $subject);
    }
}
