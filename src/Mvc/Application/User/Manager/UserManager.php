<?php

namespace AthenaCore\Mvc\Application\User\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use AthenaCore\Service\Front\JsLocalStorageTrait;
use AthenaSodium\Entity\User;
use Laminas\Session\Container;
use Poseidon\Data\DataObject;

class UserManager extends ApplicationManager
{
    use JsLocalStorageTrait;

    protected ?Container $container=null;

    protected ?User $user=null;

    public function __construct()
    {
        $this -> jsLocalStorage = new DataObject();
    }

    public function user():User{
        return $this->user;
    }

    public function isActiveUser():bool
    {
        if($this->user === null){
            return false;
        }
        return true;
    }

    public function setup(): void
    {
        $this -> container = new Container('user');
    }

    public function init(): void
    {

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