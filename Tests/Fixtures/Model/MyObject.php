<?php

namespace Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\Model;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MyObject implements \EventDispatcherAwareModelInterface
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
}
