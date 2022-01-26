<?php

namespace AthenaCore\Command\Redis;

use AthenaCore\Command\AbstractCommand;

class RedisDeleteCommand extends AbstractCommand
{

    public function execute()
    {
        $this -> applicationCore -> getCacheManager() -> removeIfExists($this -> config -> key);
    }
}