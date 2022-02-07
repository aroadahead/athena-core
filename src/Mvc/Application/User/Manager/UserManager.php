<?php

namespace AthenaCore\Mvc\Application\User\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use AthenaCore\Service\Front\JsLocalStorageTrait;
use Laminas\Session\Container;
use Poseidon\Data\DataObject;

class UserManager extends ApplicationManager
{
    use JsLocalStorageTrait;

    protected ?Container $container;

    public function __construct()
    {
        $this -> jsLocalStorage = new DataObject();
        $this -> container = new Container('user');
    }

    public function setup(): void
    {
        // TODO: Implement setup() method.
    }

    public function init(): void
    {
        // TODO: Implement init() method.
    }

    public function boot(): void
    {
        // TODO: Implement boot() method.
    }

    public function setUserLocale(string $locale): void
    {
        $this -> container -> offsetSet('locale', $locale);
        $this -> applicationCore -> getLogManager() -> debug("User locale set: " .
            $this -> container -> offsetGet('locale'));
    }
}