<?php

namespace AthenaCore\Mvc\Application\Log;

use AthenaCore\Mvc\Application\Log\Manager\LogManager;

class Facade
{
    public function __construct(protected LogManager $logManager)
    {
    }
}