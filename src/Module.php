<?php

namespace AthenaCore;

use AthenaCore\Mvc\Application\Modules\AbstractModule;

class Module extends AbstractModule
{
    public function getConfig():array
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}