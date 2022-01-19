<?php

namespace AthenaCore\Mvc\Application\Db\Manager;

use Laminas\Config\Config;
use Laminas\Db\Adapter\Adapter;

class ConnectionHandler
{
    protected ?Adapter $adapter = null;

    public function __construct(protected Config $config)
    {
        $this->adapter = new Adapter($this->config->toArray());
        $this->adapter->getDriver()->getConnection()->connect();
    }

    public function getAdapter():?Adapter
    {
        return $this->adapter;
    }
}