<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Application\Db\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use Laminas\Db\Adapter\Adapter;

class DbManager extends ApplicationManager
{

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

    public function masterAdapter():Adapter{

    }

    public function slaveAdapter():Adapter{

    }
}