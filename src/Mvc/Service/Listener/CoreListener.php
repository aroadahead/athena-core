<?php

namespace AthenaCore\Mvc\Service\Listener;

use Laminas\EventManager\EventManagerInterface;
use Psr\Container\ContainerInterface;

class CoreListener extends \Laminas\EventManager\AbstractListenerAggregate
{

    public function __construct(protected ContainerInterface $container)
    {
    }

    /**
     * @inheritDoc
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {

    }
}