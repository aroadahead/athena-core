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

    public function setArgs(array $args=[]):void
    {
        $this->config = new Config($args);
    }

    public abstract function execute();
}