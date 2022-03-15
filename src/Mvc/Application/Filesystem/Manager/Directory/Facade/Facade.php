<?php

namespace AthenaCore\Mvc\Application\Filesystem\Manager\Directory\Facade;

use AthenaCore\Mvc\Application\Filesystem\Manager\Directory\DirectoryPaths;
use function is_dir;
use function str_ireplace;

class Facade
{
    public function __construct(protected DirectoryPaths $directoryPaths)
    {
    }

    public function moduleOrVendor(string $potential, ?string $extra = null): string
    {
        $path = $this -> modules($potential);
        if (is_dir($path)) {
            return $path . DIRECTORY_SEPARATOR . $extra;
        }
        return $this -> vendor('aroadahead/' . $potential . '/' . $extra);
    }

    public function doctrine(?string $extra=null):string
    {
        return $this->getPath('doctrine',$extra);
    }

    public function doctrineProxy(?string $extra=null):string
    {
        return $this->getPath('doctrine_proxies',$extra);
    }

    public function doctrineHydrator(?string $extra=null):string
    {
        return $this->getPath('doctrine_hydrators',$extra);
    }

    public function modules(?string $extra = null): string
    {
        return $this -> getPath('modules', $extra);
    }

    public function tmp(?string $extra = null): string
    {
        return $this -> getPath('tmp', $extra);
    }

    public function root(?string $extra = null): string
    {
        return $this -> getPath('root', $extra);
    }

    public function log(?string $extra = null): string
    {
        return $this -> getPath('log', $extra);
    }

    public function forms(?string $extra = null): string
    {
        return $this -> getPath('forms', $extra);
    }

    public function docs(?string $extra = null): string
    {
        return $this -> getPath('docs', $extra);
    }

    public function html(?string $extra = null): string
    {
        return $this -> getPath('public', $extra);
    }

    public function react(?string $extra = null): string
    {
        return $this -> getPath('reactSrc', $extra);
    }

    public function assets(?string $extra = null): string
    {
        return $this -> getPath('assets', $extra);
    }

    public function data(?string $extra = null): string
    {
        return $this -> getPath('data', $extra);
    }

    public function cache(?string $extra = null): string
    {
        return $this -> getPath('cache', $extra);
    }

    public function config(?string $extra = null): string
    {
        return $this -> getPath('config', $extra);
    }

    public function session(?string $extra = null): string
    {
        return $this -> getPath('session', $extra);
    }

    public function templates(?string $extra = null): string
    {
        return $this -> getPath('templates', $extra);
    }

    public function vendor(?string $extra = null): string
    {
        return $this -> getPath('vendor', $extra);
    }

    public function language(?string $extra = null): string
    {
        return $this -> getPath('language', $extra);
    }

    private function getPath(string $name, ?string $extra = null): string
    {
        $dir = $this -> directoryPaths -> getPath($name);
        if ($extra !== null) {
            $dir .= DIRECTORY_SEPARATOR . str_ireplace(['/', '\\'], DIRECTORY_SEPARATOR, $extra);
        }
        return $dir;
    }
}