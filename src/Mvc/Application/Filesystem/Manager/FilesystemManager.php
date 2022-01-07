<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Application\Filesystem\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use AthenaCore\Mvc\Application\Filesystem\Manager\Directory\DirectoryPaths;
use function var_dump;

class FilesystemManager extends ApplicationManager
{

    protected string $rootPath;
    protected DirectoryPaths $directoryPaths;

    public function __construct()
    {
        $this -> directoryPaths = new DirectoryPaths();
    }

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        return $this -> rootPath;
    }

    /**
     * @param string $rootPath
     */
    public function setRootPath(string $rootPath): void
    {
        $this -> rootPath = $rootPath;
    }


    public function setup(): void
    {
        $rootPath = $this->getRootPath();
        $paths = [
            'config' => $rootPath.'/config',
            'data' => $rootPath.'/data',
            'log' => $rootPath.'/data/log',
            'cache' => $rootPath .'/data/cache',
            'docs' => $rootPath.'/docs',
            'public' => $rootPath.'/public',
            'reactSrc' => $rootPath.'/public/src'
        ];
        $overridePaths = [];
        $this -> directoryPaths -> loadPaths($paths, $overridePaths);
    }

    public function getDirectoryPaths():DirectoryPaths
    {
        return $this->directoryPaths;
    }

    public function getPath(string $name): string
    {
        return $this -> directoryPaths -> getPath($name);
    }

    public function init(): void
    {
        // TODO: Implement init() method.
    }

    public function boot(): void
    {
        // TODO: Implement boot() method.
    }
}