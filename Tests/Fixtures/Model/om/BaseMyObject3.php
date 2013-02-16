<?php

namespace Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\Model\om;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class BaseMyObject3 implements \EventDispatcherAwareModelInterface
{
    private static $eventDispatcher;

    /**
     * {@inheritdoc}
     */
    public static function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        self::$eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public static function getEventDispatcher()
    {
        if (null === self::$eventDispatcher) {
            self::$eventDispatcher = new EventDispatcher();
        }

        return self::$eventDispatcher;
    }
}
