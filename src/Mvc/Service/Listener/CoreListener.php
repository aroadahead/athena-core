<?php

namespace AthenaCore\Mvc\Service\Listener;

use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\MvcEvent;

class CoreListener extends AbstractServiceListener
{

    /**
     * @inheritDoc
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this -> attachAs($events, MvcEvent::EVENT_DISPATCH, [$this, 'onRoute'], $priority);
    }

    public function onRoute(MvcEvent $e): void
    {
        $routeMatch = $e -> getRouteMatch();
        $routeMatch = $e -> getRouteMatch();
        $routeMatchedName = $routeMatch -> getMatchedRouteName();
        $realController = $routeMatch -> getParam('controller');
        $className = str_replace(['\\', '_Controller_'], '_', $realController);
        $className = str_replace('Controller', '', $className);
        $className = mb_strtolower($className, "UTF-8");
        $moduleParts = explode('_', $className);
        $module = $moduleParts[0];
        $controller = $moduleParts[1];
        $action = $routeMatch -> getParam('action');
        $this -> container -> get('log') -> info("Dispatching route module: {$module}");
        $this -> container -> get('log') -> info("Dispatching route controller: {$controller}");
        $this -> container -> get('log') -> info("Dispatching route action: {$action}");
    }
}