<?php

namespace AthenaCore\Mvc\Application\Filesystem\Manager\Directory\Facade;

use AthenaCore\Mvc\Application\Filesystem\Manager\Directory\DirectoryPaths;
use function str_ireplace;

class Facade
{
    public function __construct(protected DirectoryPaths $directoryPaths)
    {

    }

    public function root(?string $extra = null): string
    {
        return $this -> getPath('root', $extra);
    }

    public function log(?string $extra = null): string
    {
        return $this -> getPath('log', $extra);
    }

    public function docs(?string $extra = null): string
    {
        return $this -> getPath('docs', $extra);
    }

    public function htmlpublic(?string $extra = null): string
    {
        return $this -> getPath('public', $extra);
    }

    public function reactSrc(?string $extra = null): string
    {
        return $this -> getPath('reactSrc', $extra);
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

    public function getPath(string $name, ?string $extra = null): string
    {
        $dir = $this -> directoryPaths -> getPath($name);
        if ($extra !== null) {
            $dir .= DIRECTORY_SEPARATOR . str_ireplace(['/', '\\'], DIRECTORY_SEPARATOR, $extra);
        }
        return $dir;
    }

    public function session(?string $extra = null): string
    {
        return $this -> getPath('session', $extra);
    }

    public function language(?string $extra = null): string
    {
        return $this -> getPath('language', $extra);
    }
}