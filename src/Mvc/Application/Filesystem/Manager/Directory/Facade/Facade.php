<?php

namespace AthenaCore\Mvc\Application\Filesystem\Manager\Directory\Facade;

use AthenaCore\Mvc\Application\Filesystem\Manager\Directory\DirectoryPaths;

class Facade
{
    public function __construct(protected DirectoryPaths $directoryPaths)
    {

    }

    public function log(): string
    {
        return $this -> getPath('log');
    }

    public function docs(): string
    {
        return $this -> getPath('docs');
    }

    public function htmlpublic(): string
    {
        return $this -> getPath('public');
    }

    public function reactSrc(): string
    {
        return $this -> getPath('reactSrc');
    }

    public function data(): string
    {
        return $this -> getPath('data');
    }

    public function cache(): string
    {
        return $this -> getPath('cache');
    }

    public function config(): string
    {
        return $this -> getPath('config');
    }

    public function getPath(string $name): string
    {
        return $this -> directoryPaths -> getPath($name);
    }
}