<?php

namespace Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\Model;

use Bazinga\Bundle\PropelEventDispatcherBundle\Tests\Fixtures\Model\om\BaseMyObject3;
use Symfony\Component\EventDispatcher\GenericEvent;

class MyObject3 extends BaseMyObject3
{
    public $source = null;

    public function preSave()
    {
        self::getEventDispatcher()->dispatch('propel.pre_save', new GenericEvent($this));
    }

    public function preInsert()
    {
        self::getEventDispatcher()->dispatch('propel.pre_insert', new GenericEvent($this));
    }
}
