<?php

namespace AthenaCore\Command\Redis;

use AthenaCore\Command\AbstractCommand;

class RedisDeleteCommand extends AbstractCommand
{

    public function execute()
    {
        $this -> applicationCore -> getCacheManager() -> removeData($this -> config -> key);
        $this -> applicationCore -> getLogManager() -> debug("Deleted key: {$this->config->key}");
    }
}