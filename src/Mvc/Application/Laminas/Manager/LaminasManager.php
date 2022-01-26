<?php

namespace AthenaCore\Mvc\Application\Laminas\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use Psr\Container\ContainerInterface;

class LaminasManager extends ApplicationManager
{
    protected ContainerInterface $container;

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getContainer():ContainerInterface
    {
        return $this->container;
    }

    public function setup(): void
    {
        // TODO: Implement setup() method.
    }

    public function init(): void
    {
        // TODO: Implement init() method.
    }

    public function boot(): void
    {
        // TODO: Implement boot() method.
    }
}