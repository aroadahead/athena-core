<?php

namespace AthenaCore\View\Helper\Mvc\Factory;

use AthenaCore\View\Helper\Mvc\Config;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ConfigFactory implements FactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new Config($container);
    }
}