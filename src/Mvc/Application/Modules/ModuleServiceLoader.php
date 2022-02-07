<?php

namespace AthenaCore\Mvc\Application\Modules;

use AthenaCore\Mvc\Application\Laminas\StandardContainer;
use AthenaCore\Mvc\Service\MvcService;
use Poseidon\Data\DataObject;
use Psr\Container\ContainerInterface;

class ModuleServiceLoader
{
    protected ?DataObject $modulesLoaded = null;
    protected ?ContainerInterface $container = null;

    public function __construct()
    {
        $this -> modulesLoaded = new DataObject();

    }

    public function load(string $moduleService): MvcService
    {
        if (!$this -> modulesLoaded -> hasItem($moduleService)) {
            if ($this -> container === null) {
                $this -> container = StandardContainer ::getPsrContainer();
            }
            $moduleServiceClazz = $this -> container -> get('module.service.' . $moduleService);
            $this -> modulesLoaded -> setItem($moduleService, $moduleServiceClazz);
        }
        return $this -> modulesLoaded -> getItem($moduleService);
    }
}