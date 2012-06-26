<?php

namespace Bazinga\Bundle\PropelEventDispatcherBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\Event;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class RegisterEventListenersPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $classes = array();
        foreach ($container->findTaggedServiceIds('propel.event_listener') as $id => $attributes) {
            foreach ($attributes as $attrs) {
                $class  = $attrs['class'];
                $event  = $attrs['event'];
                $method = $this->getMethodFromEvent($event);
                $servId = $this->getServiceIdForClass($class);

                if (!isset($classes[$servId])) {
                    // create a new EventDispatcher service
                    $service = $container
                        ->register($servId)
                        ->setClass($container->getParameter('bazinga.propel_event_dispatcher.event_dispatcher.class'))
                        ->setArguments(array(new Reference('service_container')))
                        ;

                    $classes[$servId] = $class;
                } else {
                    $service = $container->getDefinition($servId);
                }

                $service
                    ->addMethodCall('addListenerService', array(
                        $event,
                        array($id, $method)
                    ));
            }
        }

        $container->setParameter('bazinga.propel_event_dispatcher.registered_classes', $classes);
    }

    /**
     * @param string $event
     * @return string
     */
    private function getMethodFromEvent($event)
    {
        $event = str_replace('propel.', '', $event);

        return lcfirst(str_replace(" ", "", ucwords(strtr($event, "_-", "  "))));
    }

    /**
     * @param string $class
     * @return string
     */
    private function getServiceIdForClass($class)
    {
        return 'bazinga.propel_event_dispatcher.' . strtolower(str_replace('\\', '_', $class));
    }
}
