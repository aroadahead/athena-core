<?php

namespace AthenaCore\Mvc\Service\Listener;

use Laminas\EventManager\EventManagerInterface;
use Psr\Container\ContainerInterface;
use function explode;
use function get_class;

abstract class AbstractServiceListener extends \Laminas\EventManager\AbstractListenerAggregate
{
    protected string $clazzName;

    public function __construct(protected ContainerInterface $container)
    {
        $clazz = get_class($this);
        $parts = explode('/',$clazz);
        $this->clazzName = $parts[count($parts)-1];
        $this -> container -> get('log') -> info("Listener {$this->clazzName} initialized.");
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

    public function markTriggered(): void
    {
        $this -> container -> get('log') -> info("Listener {$this->clazzName} triggered");
    }
}