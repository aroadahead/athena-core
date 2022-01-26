<?php

namespace AthenaCore\Mvc\Service\Listener\Factory;

use AthenaCore\Mvc\Service\Listener\CoreListener;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class CoreListenerFactory implements FactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new CoreListener($container);
    }
}