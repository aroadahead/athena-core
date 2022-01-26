<?php

namespace AthenaCore;

use AthenaCore\Mvc\Application\Modules\AbstractModule;

class Module extends AbstractModule
{
    protected string $dir = __DIR__;

    public function getConfig():array
    {
        return include $this->dir . '/../config/module.config.php';
    }
}