<?php

namespace Bazinga\Bundle\PropelEventDispatcherBundle\EventDispatcher;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LazyEventDispatcher implements EventDispatcherInterface
{
    private $container;

    private $serviceId;

    private $eventDispatcher = null;

    public function __construct($container, $serviceId)
    {
        $this->container = $container;
        $this->serviceId = $serviceId;
    }

    public function dispatch($eventName, Event $event = null)
    {
        return $this->getEventDispatcher()->dispatch($eventName, $event);
    }

    public function addListener($eventName, $listener, $priority = 0)
    {
        $this->getEventDispatcher()->addListener($eventName, $listener, $priority);
    }

    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->getEventDispatcher()->addSubscriber($subscriber);
    }

    public function removeListener($eventName, $listener)
    {
        $this->getEventDispatcher()->removeListener($eventName, $listener);
    }

    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->getEventDispatcher()->removeSubscriber($subscriber);
    }

    public function getListeners($eventName = null)
    {
        return $this->getEventDispatcher()->getListeners($eventName);
    }

    public function hasListeners($eventName = null)
    {
        return $this->getEventDispatcher()->hasListeners($eventName);
    }

    protected function getEventDispatcher()
    {
        if (null === $this->eventDispatcher) {
            $this->eventDispatcher = $this->container->get($this->serviceId);
        }

        return $this->eventDispatcher;
    }
}
