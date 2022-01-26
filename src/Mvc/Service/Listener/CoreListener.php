<?php

namespace AthenaCore\Mvc\Service\Listener;

use Laminas\EventManager\EventManagerInterface;
use Laminas\Json\Json;
use Laminas\Mvc\MvcEvent;
use Poseidon\Poseidon;

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
        $this->markTriggered();
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
        $log = $this -> container -> get('log');
        $log -> info("Dispatching Controller Class: " . $routeMatch -> getParam('controller'));
        $log -> info("Dispatching route matchedName: {$routeMatchedName}");
        $log -> info("Dispatching route module: {$module}");
        $log -> info("Dispatching route controller: {$controller}");
        $log -> info("Dispatching route action: {$action}");
        $tmp = $routeMatch -> getParams();
        unset($tmp['controller'], $tmp['action']);
        $log -> info("Dispatching route params: " . Json ::encode($tmp));

        $registry = Poseidon ::registry();
        if (!$registry -> has('app.route.matchedName')) {
            $registry -> add('app.route.matchedName', $routeMatchedName);
        }
        if (!$registry -> has('app.route.params')) {
            $registry -> add('app.route.params', $routeMatch -> getParams());
        }
        if (!$registry -> has('app.route.fullRouteController')) {
            $registry -> add('app.route.fullRouteController', $realController);
        }
        if (!$registry -> has('app.route.module')) {
            $registry -> add('app.route.module', $module);
        }
        if (!$registry -> has('app.route.controller')) {
            $registry -> add('app.route.controller', $controller);
        }
        if (!$registry -> has('app.route.action')) {
            $registry -> add('app.route.action', $action);
        }
        $serverVars = ['UNIQUE_ID', 'HTTP_USER_AGENT', 'SERVER_ADDR', 'REMOTE_ADDR', 'REQUEST_TIME'];
        foreach ($serverVars as $var) {
            $key = "app.server." . strtolower(str_replace('_', '.', $var));
            $val = $e -> getRequest() -> getServer($var);
            if (!$registry -> has($key)) {
                $registry -> add($key, $val);
            }
            $log -> info("Server $var:  $val");
        }
    }
}