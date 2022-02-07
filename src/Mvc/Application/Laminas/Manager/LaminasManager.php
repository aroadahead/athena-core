<?php

namespace AthenaCore\Mvc\Application\Laminas\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use AthenaCore\Mvc\Application\Laminas\RouteNotFound;
use Psr\Container\ContainerInterface;
use function array_key_exists;

class LaminasManager extends ApplicationManager
{
    protected ContainerInterface $container;
    protected ?array $routes = [];

    public function setContainer(ContainerInterface $container)
    {
        $this -> container = $container;
    }

    public function route(string $route, string $module): string
    {
        if (array_key_exists($route, $this -> routes[$module])) {
            return $this -> routes[$module][$route];
        }
        throw new RouteNotFound("Route $route in module $module not found!");
    }

    public function getContainer(): ContainerInterface
    {
        return $this -> container;
    }

    public function setup(): void
    {
        $this -> routes = $this -> applicationCore -> getConfigManager() -> lookup('routes', true);
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