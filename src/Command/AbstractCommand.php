<?php

namespace AthenaCore\Command;

use AthenaCore\Mvc\Application\Application\Core\ApplicationCore;
use Laminas\Config\Config;
use Poseidon\Poseidon;
use Psr\Container\ContainerInterface;

abstract class AbstractCommand
{
    protected ApplicationCore $applicationCore;
    protected Config $config;

    public function __construct(protected ContainerInterface $container)
    {
        $this->applicationCore = $this->container->get('core');
    }

    public function setArgs(Config $config):void
    {
        $this->config = $config;
    }

    public abstract function execute();
}