<?php

namespace AthenaCore\Mvc\Application\Config\Facade;

use AthenaCore\Mvc\Application\Config\Manager\ConfigManager;
use AthenaCore\Service\Front\JsLocalStorageTrait;
use Poseidon\Data\DataObject;

class Facade
{
    use JsLocalStorageTrait;

    public function __construct(protected ConfigManager $configManager)
    {
        $this->jsLocalStorage = new DataObject();
    }
}