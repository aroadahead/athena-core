<?php

declare(strict_types=1);

namespace AthenaCore\Application\Controller\Factory;

use AthenaCore\Application\Controller\IndexController;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use ReflectionException;

class IndexControllerFactory implements FactoryInterface
{
    /**
     * @throws ReflectionException
     *
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new IndexController($container);
    }
}
