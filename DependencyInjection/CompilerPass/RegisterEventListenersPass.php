<?php

namespace Bazinga\Bundle\PropelEventDispatcherBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterEventListenersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('propel.event_listener') as $id => $attributes) {
            $class = $attributes['class'];
            $event = $attributes['event'];

            if (method_exists($class, 'getEventDispatcher')) {
                $class::getEventDispatcher()->addListener($event, new Reference($id));
            }
        }
    }
}
