<?php

namespace AthenaCore\Command\Redis\Factory;

use AthenaCore\Command\Redis\RedisDeleteCommand;
use Interop\Container\ContainerInterface;

class RedisDeleteCommandFactory implements \Laminas\ServiceManager\Factory\FactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new RedisDeleteCommand($container);
    }
}