<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Application\Db\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\TableGateway\Feature\GlobalAdapterFeature;

class DbManager extends ApplicationManager
{
    protected ?ConnectionHandler $masterConnection = null;
    protected ?ConnectionHandler $slaveConnection = null;

    public function setup(): void
    {
        $config = $this -> applicationCore -> getConfigManager() -> lookup('db');
        $this -> masterConnection = new ConnectionHandler($config -> master);
        $this -> slaveConnection = new ConnectionHandler($config -> slave);

        GlobalAdapterFeature::setStaticAdapter($this->masterAdapter());
    }

    public function init(): void
    {
        // TODO: Implement init() method.
    }

    public function boot(): void
    {
        // TODO: Implement boot() method.
    }

    public function masterAdapter(): Adapter
    {
        return $this -> masterConnection -> getAdapter();
    }

    public function slaveAdapter(): Adapter
    {
        return $this -> slaveConnection -> getAdapter();
    }
}