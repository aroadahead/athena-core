<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Application\Db\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\TableGateway\Feature\GlobalAdapterFeature;
use function sprintf;

class DbManager extends ApplicationManager
{
    protected ?ConnectionHandler $masterConnection = null;
    protected ?ConnectionHandler $slaveConnection = null;
    protected ?Connection $doctrineConnection;

    /**
     * @throws Exception
     */
    public function setup(): void
    {
        $config = $this -> applicationCore -> getConfigManager() -> lookup('db');
        $this -> masterConnection = new ConnectionHandler($config -> master);
        $this -> slaveConnection = new ConnectionHandler($config -> slave);

        GlobalAdapterFeature ::setStaticAdapter($this -> masterAdapter());

        $dsn = sprintf("mysql://%s:%s@%s:%d/%s?charset=%s", $config -> master -> username, $config -> master -> password,
            $config -> master -> hostname, $config -> master -> port, $config -> master -> database,
            $config -> master -> charset);
        $this -> doctrineConnection = DriverManager ::getConnection(['url'=>$dsn]);
    }

    public function getDoctrineConnection(): Connection
    {
        return $this -> doctrineConnection;
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