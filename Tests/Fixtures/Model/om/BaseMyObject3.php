<?php

namespace Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\Model\om;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class BaseMyObject3 implements \EventDispatcherAwareModelInterface
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
        if (null === self::$eventDispatcher) {
            self::$eventDispatcher = new EventDispatcher();
        }

        return self::$eventDispatcher;
    }
}
