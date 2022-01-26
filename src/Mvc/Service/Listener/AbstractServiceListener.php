<?php

namespace AthenaCore\Mvc\Service\Listener;

use Laminas\EventManager\EventManagerInterface;
use Psr\Container\ContainerInterface;
use function get_class;

abstract class AbstractServiceListener extends \Laminas\EventManager\AbstractListenerAggregate
{

    public function __construct(protected ContainerInterface $container)
    {
        $this -> container -> get('log') -> info("Listener " . get_class($this) . " initialized.");
    }


    /**
     * @inheritDoc
     */
    public function attachShared(EventManagerInterface $events, string $ident, string $event, array $call, int $priority = 1)
    {
        $events -> getSharedManager() -> attach($ident, $event, $call, $priority);
    }

    public function attachAs(EventManagerInterface $events, string $event, array $call, int $priority = 1)
    {
        $this -> listeners[] = $events -> attach($event, $call, $priority);
    }
}