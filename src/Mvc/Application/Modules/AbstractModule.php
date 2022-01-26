<?php

namespace AthenaCore\Mvc\Application\Modules;

use Laminas\ModuleManager\ModuleManagerInterface;

abstract class AbstractModule
{
    protected string $namespaceName;
    protected ModuleManagerInterface $moduleManager;
}