<?php

namespace Bazinga\Bundle\PropelEventDispatcherBundle;

use Bazinga\Bundle\PropelEventDispatcherBundle\DependencyInjection\CompilerPass\RegisterEventListenersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class BazingaPropelEventDispatcherBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterEventListenersPass());
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->container->get('bazinga.propel_event_dispatcher.injector')->initializeModels();
    }
}
