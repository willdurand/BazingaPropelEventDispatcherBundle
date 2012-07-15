<?php

namespace Bazinga\Bundle\PropelEventDispatcherBundle;

use Bazinga\Bundle\PropelEventDispatcherBundle\EventDispatcher\LazyEventDispatcher;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

class DispatcherInjector
{
    private $classes;
    private $container;
    private $logger;

    public function __construct(ContainerInterface $container, array $classes, LoggerInterface $logger = null)
    {
        $this->classes = $classes;
        $this->container = $container;
        $this->logger = $logger;
    }

    /**
     * Initializes the EventDispatcher-aware models.
     *
     * This methods has to accept unknown classes as it is triggered during
     * the boot and so will be called before running the propel:build command.
     */
    public function initializeModels()
    {
        foreach ($this->classes as $id => $class) {
            try {
                $ref = new \ReflectionClass($class);
            } catch (\ReflectionException $e) {
                $this->log(sprintf('The class "%s" does not exist. Either your model is not generated yet or you have an error in your listener configuration.', $class));
                continue;
            }

            $interface = 'EventDispatcherAwareModelInterface';
            if (!$ref->implementsInterface($interface)) {
                $this->log(sprintf('The class "%s" does not implement "%s". Either your model is outdated or you forgot to add the EventDispatcherBehavior.', $class, $interface));
                continue;
            }

            $class::setEventDispatcher(new LazyEventDispatcher($this->container, $id));
        }
    }

    private function log($message)
    {
        if (null !== $this->logger) {
            $this->logger->warn($message);
        }
    }
}
