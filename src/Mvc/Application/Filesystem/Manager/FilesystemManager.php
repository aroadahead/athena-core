<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Application\Filesystem\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;

class FilesystemManager extends ApplicationManager
{

    protected string $rootPath;

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
        // TODO: Implement setup() method.
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