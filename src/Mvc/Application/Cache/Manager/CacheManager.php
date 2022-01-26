<?php

declare(strict_types=1);


namespace AthenaCore\Mvc\Application\Cache\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use Laminas\Serializer\Adapter\PhpSerialize;
use PHPUnit\TextUI\XmlConfiguration\Php;
use Predis\Client;

class CacheManager extends ApplicationManager
{
    protected Client $client;
    protected PhpSerialize $serialize;

    public function setup(): void
    {
        $this->serialize = new PhpSerialize();
        $config = $this->applicationCore->getEnvironmentManager()->getRedisConfig()->toArray();
        $this->client = new Client($config);
        $this->client->connect();
    }

    public function init(): void
    {

    }

    public function removeIfExists(string $key):void
    {
        if($this->hasData($key)){
            $this->removeData($key);
        }
    }

    public function removeData(string $key):void
    {
        $this->client->del($key);
        $this->applicationCore->getLogManager()->debug("Deleted redis key $key");
    }

    public function setData(string $key, mixed $value, int $ttl = null): void
    {
        if ($ttl===null) {
            $this -> client -> set($key, $value);
        } else {
            $this -> client -> setex($key, $ttl, $value);
        }
    }

    public function getDataAsArrayOrObject(string $key): mixed
    {
        return $this -> serialize -> unserialize($this -> getData($key));
    }

    public function getData(string $key): mixed
    {
        return $this -> client -> get($key);
    }

    public function setDataAsArrayOrObject(string $key, mixed $value): void
    {
        $this -> setData($key, $this -> serialize -> serialize($value));
    }

    public function hasData(string $key):bool
    {
        if($this->client->exists($key)){
            return true;
        }
        return false;
    }

    public function boot(): void
    {
        // TODO: Implement boot() method.
    }
}