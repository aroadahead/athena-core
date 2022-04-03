<?php

namespace AthenaCore\Mvc\Service\Listener;

use Laminas\EventManager\EventManagerInterface;
use Psr\Container\ContainerInterface;
use function get_class;

abstract class AbstractServiceListener extends \Laminas\EventManager\AbstractListenerAggregate
{
    protected string $clazzName;
    protected string $eventName;
    protected const ABSTRACT_CONTROLLER_RESOURCE = 'Laminas\Mvc\Controller\AbstractController';

    public function __construct(protected ContainerInterface $container)
    {
        $this -> clazzName = get_class($this);
        $this -> container -> get('log') -> info("Listener {$this->clazzName} initialized.");
    }


    /**
     * @inheritDoc
     */
    public function attachShared(EventManagerInterface $events, string $ident, string $event, array $call, int $priority = 1)
    {
        $this -> eventName = $event;
        $events -> getSharedManager() -> attach($ident, $event, $call, $priority);
    }

    public function attachAs(EventManagerInterface $events, string $event, array $call, int $priority = 1)
    {
        $this -> eventName = $event;
        $this -> listeners[] = $events -> attach($event, $call, $priority);
    }

    public function markTriggered(): void
    {
        $this -> container -> get('log') -> info("Listener {$this->clazzName} triggered with mvc listener {$this->eventName}.");
    }
}