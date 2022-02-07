<?php

namespace AthenaCore\Mvc\Application\Laminas;

use AthenaCore\Mvc\Service\MvcService;
use Poseidon\Data\DataObject;
use Psr\Container\ContainerInterface;

class ModuleServiceLoader
{
    protected ?DataObject $modulesLoaded = null;

    public function __construct(protected ContainerInterface $container)
    {
        $this -> modulesLoaded = new DataObject();
    }

    public function load(string $moduleService): MvcService
    {
        if (!$this -> modulesLoaded -> hasItem($moduleService)) {
            $moduleServiceClazz = $this -> container -> get('module.service.' . $moduleService);
            $this -> modulesLoaded -> setItem($moduleService, $moduleServiceClazz);
        }
        return $this -> modulesLoaded -> getItem($moduleService);
    }
}