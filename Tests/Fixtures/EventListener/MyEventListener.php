<?php

namespace Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\EventListener;

use Symfony\Component\EventDispatcher\Event;

class MyEventListener
{
    private $events = array();

    public function preSave(Event $event)
    {
        $this->events[] = $event;
    }

    public function getEvents()
    {
        return $this->events;
    }
}
