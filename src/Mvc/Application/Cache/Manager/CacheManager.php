<?php

declare(strict_types=1);


namespace AthenaCore\Mvc\Application\Cache\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use AthenaCore\Mvc\Application\Cache\Facade\CacheFacade;
use Predis\Client;

class CacheManager extends ApplicationManager
{
    protected Client $client;

    protected ?CacheFacade $facade = null;

    public function setup(): void
    {

        $config = $this -> applicationCore -> getEnvironmentManager() -> getRedisConfig() -> toArray();
        $this -> client = new Client($config);
        $this -> client -> connect();
    }

    public function facade(): CacheFacade
    {
        return $this -> facade;
    }

    public function init(): void
    {
        $this -> facade = new CacheFacade($this);
    }

    public function client(): Client
    {
        return $this -> client;
    }


    public function boot(): void
    {
        // TODO: Implement boot() method.
    }
}