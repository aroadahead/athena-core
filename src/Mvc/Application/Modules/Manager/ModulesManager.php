<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Application\Modules\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use AthenaCore\Mvc\Application\Modules\ModuleServiceLoader;

class ModulesManager extends ApplicationManager
{
    protected ?ModuleServiceLoader $moduleServiceLoader = null;

    public function setup(): void
    {
        $this -> moduleServiceLoader = new ModuleServiceLoader($this -> applicationCore
            -> getLaminasManager() -> getContainer());
    }

    public function init(): void
    {
        // TODO: Implement init() method.
    }

    public function boot(): void
    {
        // TODO: Implement boot() method.
    }

    public function moduleLoader():ModuleServiceLoader
    {
        return $this->moduleServiceLoader;
    }
}