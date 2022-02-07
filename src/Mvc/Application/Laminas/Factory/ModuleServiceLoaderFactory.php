<?php

namespace AthenaCore\Mvc\Application\Laminas\Factory;

use AthenaCore\Mvc\Application\Laminas\ModuleServiceLoader;
use Interop\Container\ContainerInterface;

class ModuleServiceLoaderFactory implements \Laminas\ServiceManager\Factory\FactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new ModuleServiceLoader($container);
    }
}