<?php

declare(strict_types=1);


namespace AthenaCore\Mvc\Application\Cache\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use Predis\Client;

class CacheManager extends ApplicationManager
{
    protected Client $client;

    public function setup(): void
    {

    }

    public function init(): void
    {
        $config = $this->applicationCore->getConfigManager()->lookup('redis',true);
        $this->client = new Client($config);
        $this->client->connect();
    }

    public function boot(): void
    {
        // TODO: Implement boot() method.
    }
}