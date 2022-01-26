<?php

namespace AthenaCore\Mvc\Application\Modules;

use AthenaCore\Mvc\Application\Application\Core\ApplicationCore;
use Laminas\EventManager\EventInterface;
use Laminas\ModuleManager\ModuleEvent;
use Laminas\ModuleManager\ModuleManagerInterface;
use Laminas\Mvc\ModuleRouteListener;
use Poseidon\Poseidon;

abstract class AbstractModule
{
    protected string $namespaceName;
    protected ModuleManagerInterface $moduleManager;
    protected ApplicationCore $applicationCore;

    public function __construct()
    {
        $this->applicationCore = Poseidon::getCore();
        $this->namespaceName = (new \ReflectionClass($this))->getNamespaceName();
    }

    public function getModuleManager():ModuleManagerInterface
    {
        return $this->moduleManager;
    }

    public function getNamespaceName():string
    {
        return $this->namespaceName;
    }

    public function init(ModuleManagerInterface $manager)
    {
        $this->moduleManager = $manager;
        $events = $this->moduleManager->getEventManager();
        $events->attach(ModuleEvent::EVENT_MERGE_CONFIG,[$this,'onMergeConfig']);
        $events -> attach(ModuleEvent::EVENT_LOAD_MODULES, [$this, 'loadModules']);
        $events -> attach(ModuleEvent::EVENT_LOAD_MODULES_POST, [$this, 'modulesLoaded']);
        $events -> attach(ModuleEvent::EVENT_LOAD_MODULE_RESOLVE, [$this, 'loadModuleResolve']);
        $events -> attach(ModuleEvent::EVENT_LOAD_MODULE, [$this, 'loadModule']);
    }

    public function onBootstrap(EventInterface $e): void
    {
        $app = $e->getApplication();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($app->getEventManager());
    }

    public function loadModule(ModuleEvent $e)
    {
    }

    public function loadModuleResolve(ModuleEvent $e)
    {
    }

    public function loadModules(ModuleEvent $e)
    {
    }

    public function modulesLoaded(ModuleEvent $e)
    {
        $moduleManager = $e -> getTarget();
        $loadedModules = $moduleManager -> getLoadedModules();
    }

    public function onMergeConfig(ModuleEvent $e)
    {
    }
}