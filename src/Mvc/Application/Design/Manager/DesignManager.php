<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Application\Design\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;

class DesignManager extends ApplicationManager
{
    protected int $designPackageId = 1;

    public function getDesignPackageId(): int
    {
        return $this -> designPackageId;
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