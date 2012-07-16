<?php

namespace Bazinga\Bundle\PropelEventDispatcherBundle\Tests;

class BazingaPropelEventDispatcherBundleTest extends WebTestCase
{
    public function testGetListener()
    {
        $listener = $this->getContainer()->get('listener.my_event_listener');

        $this->assertNotNull($listener);
        $this->assertInstanceOf('Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\EventListener\MyEventListener', $listener);
    }
}
