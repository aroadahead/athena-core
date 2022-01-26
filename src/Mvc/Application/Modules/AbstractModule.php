<?php

namespace AthenaCore\Mvc\Application\Modules;

use AthenaCore\Mvc\Application\Application\Core\ApplicationCore;
use Laminas\Config\Config;
use Laminas\EventManager\EventInterface;
use Laminas\Json\Json;
use Laminas\ModuleManager\ModuleEvent;
use Laminas\ModuleManager\ModuleManagerInterface;
use Laminas\Mvc\ModuleRouteListener;
use Poseidon\Poseidon;
use function file_exists;

abstract class AbstractModule
{
    protected string $namespaceName;
    protected ModuleManagerInterface $moduleManager;
    protected ApplicationCore $applicationCore;
    protected string $dir;
    protected Config $configMaster;

    public function __construct()
    {
        $this -> applicationCore = Poseidon ::getCore();
        $this -> namespaceName = (new \ReflectionClass($this)) -> getNamespaceName();
    }


    public function getConfig(): array
    {
        return include $this -> applicationCore -> getFilesystemManager()
                -> realPath($this -> dir . '/../') . '/config/laminas.module.config.php';
    }

    public function getModuleManager(): ModuleManagerInterface
    {
        return $this -> moduleManager;
    }

    public function getNamespaceName(): string
    {
        return $this -> namespaceName;
    }

    public function init(ModuleManagerInterface $manager)
    {
        $this -> moduleManager = $manager;
        $events = $this -> moduleManager -> getEventManager();
        $events -> attach(ModuleEvent::EVENT_MERGE_CONFIG, [$this, 'onMergeConfig']);
        $events -> attach(ModuleEvent::EVENT_LOAD_MODULES, [$this, 'loadModules']);
        $events -> attach(ModuleEvent::EVENT_LOAD_MODULES_POST, [$this, 'modulesLoaded']);
        $events -> attach(ModuleEvent::EVENT_LOAD_MODULE_RESOLVE, [$this, 'loadModuleResolve']);
        $events -> attach(ModuleEvent::EVENT_LOAD_MODULE, [$this, 'loadModule']);
    }

    public function onBootstrap(EventInterface $e): void
    {
        $app = $e -> getApplication();
        $em = $app -> getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener -> attach($em);
        $modConfigKey = $this -> namespaceName . '_AthenaModuleConfig';
        $flushKey = $modConfigKey . '_flush';
        $cache = $this -> applicationCore -> getCacheManager();
        if ($cache -> hasData($flushKey)) {
            $cache -> removeData($modConfigKey);
            if ($cache -> hasData($modConfigKey)) {
                throw new \Exception("cannot remove cache config $modConfigKey");
            }
            $cache -> removeData($flushKey);
        }
        $env = $this -> applicationCore -> getEnvironmentManager();
        if ($cache -> hasData($modConfigKey) && !$env -> isDevelopmentEnvironment()) {
            $config = $cache -> getDataAsArrayOrObject($modConfigKey);
        } else {
            $modConfigFile = $this -> applicationCore -> getFilesystemManager()
                    -> realPath($this -> dir . '/../') . '/config/athena.module.config.php';
            if (file_exists($modConfigFile)) {
                $config = new Config(include_once $modConfigFile);
                if (!$env -> isDevelopmentEnvironment()) {
                    $cache -> setDataAsArrayOrObject($modConfigKey, $config);
                }
            } else {
                $config = new Config([], true);
            }
        }
        $sm = $app -> getServiceManager();
        if (isset($config -> commands)) {
            foreach ($config -> commands as $command) {
                if ($command -> enabled) {
                    $this -> applicationCore -> getLogManager() -> debug(
                        "{$this->namespaceName}: Executing Command {$command -> service} with 
                        config: ".Json::encode($command->args).'.');
                    $service = $sm -> get($command -> service);
                    $service -> setArgs($command -> args);
                    $service -> execute();
                    $this -> applicationCore -> getLogManager() -> debug(
                        "{$this->namespaceName}: Command {$command -> service} executed.");
                } else {
                    $this -> applicationCore -> getLogManager() -> debug(
                        "{$this->namespaceName}: Command {$command -> service} not enabled.");
                }
            }
        }
        if (isset($config -> listeners)) {
            foreach ($config -> listeners as $listener) {
                if ($listener -> enabled) {
                    $this -> applicationCore -> getLogManager() -> debug(
                        "{$this->namespaceName}: Attaching service {$listener->service}.");
                    $service = $sm -> get($listener -> service);
                    $service -> attach($em, $listener -> priority);
                    $this -> applicationCore -> getLogManager() -> debug(
                        "{$this->namespaceName}: {$listener->service} service attached.");
                } else {
                    $this -> applicationCore -> getLogManager() -> debug(
                        "{$this->namespaceName}: {$listener->service} service not enabled.");
                }
            }
        }
        $this -> configMaster = $config;
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