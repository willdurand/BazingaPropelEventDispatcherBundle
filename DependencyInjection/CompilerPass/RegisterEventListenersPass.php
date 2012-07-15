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
                if (!isset($attrs['class'])) {
                    throw new \InvalidArgumentException(sprintf('Service "%s" must define the "class" attribute on "propel.event_listener" tags.', $id));
                }
                if (!isset($attrs['event'])) {
                    throw new \InvalidArgumentException(sprintf('Service "%s" must define the "event" attribute on "propel.event_listener" tags.', $id));
                }

                $event  = $attrs['event'];
                if (!isset($event['method'])) {
                    $event['method'] = $this->getMethodFromEvent($event);
                }
                $priority = isset($event['priority']) ? $event['priority'] : 0;

                $this->getDispatcherForClass($container, $attrs['class'])
                    ->addMethodCall('addListenerService', array(
                        $event,
                        array($id, $event['method']),
                        $priority,
                    ));

                $classes[] = $attrs['class'];
            }
        }

        foreach ($container->findTaggedServiceIds('propel.event_subscriber') as $id => $attributes) {
            // We must assume that the class value has been correctly filled, even if the service is created by a factory
            $class = $container->getDefinition($id)->getClass();

            $refClass = new \ReflectionClass($class);
            $interface = 'Symfony\Component\EventDispatcher\EventSubscriberInterface';
            if (!$refClass->implementsInterface($interface)) {
                throw new \InvalidArgumentException(sprintf('Service "%s" must implement interface "%s".', $id, $interface));
            }

            foreach ($attributes as $attrs) {
                if (!isset($attrs['class'])) {
                    throw new \InvalidArgumentException(sprintf('Service "%s" must define the "class" attribute on "propel.event_subscriber" tags.', $id));
                }

                $this->getDispatcherForClass($container, $attrs['class'])
                    ->addMethodCall('addSubscriberService', array($id, $class));

                $classes[] = $attrs['class'];
            }
        }

        $container->setParameter('bazinga.propel_event_dispatcher.registered_classes', array_unique($classes));
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $class
     *
     * @return \Symfony\Component\DependencyInjection\Definition
     */
    private function getDispatcherForClass(ContainerBuilder $container, $class)
    {
        $id = $this->getServiceIdForClass($class);

        if ($container->hasDefinition($id)) {
            return $container->getDefinition($id);
        }

        // create a new EventDispatcher service
        $service = $container
            ->register($id)
            ->setClass('%bazinga.propel_event_dispatcher.event_dispatcher.class%')
            ->setArguments(array(new Reference('service_container')))
            ;

        return $service;
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
