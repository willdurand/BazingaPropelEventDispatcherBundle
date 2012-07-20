<?php

namespace Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\Model;

use Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\Model\om\BaseMyObject;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class MyObject extends BaseMyObject implements \EventDispatcherAwareModelInterface
{
    static private $eventDispatcher;

    /**
     * {@inheritdoc}
     */
    static public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        self::$eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    static public function getEventDispatcher()
    {
        return self::$eventDispatcher;
    }

    public function preSave()
    {
        self::$eventDispatcher->dispatch('propel.pre_save', new GenericEvent($this));
    }
}
