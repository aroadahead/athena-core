<?php

namespace AthenaCore\Mvc\Application\Filesystem\Manager\Directory\Facade;

use AthenaCore\Mvc\Application\Filesystem\Manager\Directory\DirectoryPaths;

class Facade
{
    public function __construct(protected DirectoryPaths $directoryPaths)
    {

    }

    public function configLaminas():string
    {
        return $this->config().'/laminas';
    }

    public function configProject():string
    {
        return $this->config().'/project';
    }

    public function config():string
    {
        return $this->directoryPaths->getPath('config');
    }
}