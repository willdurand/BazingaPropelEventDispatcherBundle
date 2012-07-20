<?php

namespace Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MyEventSubscriber implements EventSubscriberInterface
{
    private $events = array();

    public function preInsert(Event $event)
    {
        $subject = $event->getSubject();
        $subject->source = 'pre_insert';

        $this->events[] = $event;
    }

    public function getEvents()
    {
        return $this->events;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'propel.pre_insert' => 'preInsert',
        );
    }
}
