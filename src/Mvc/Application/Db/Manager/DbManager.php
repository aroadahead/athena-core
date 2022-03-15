<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Application\Db\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\TableGateway\Feature\GlobalAdapterFeature;

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

        $this -> doctrineConnection = DriverManager ::getConnection([
            'dbname' => $config -> master -> database,
            'user' => $config -> master -> username,
            'password' => $config -> master -> password,
            'host' => $config -> master -> hostname,
            'driver' => strtolower($config -> master -> driver),
            'charset' => $config -> master -> charset,
            'collation' => $config -> master -> collation
        ]);
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