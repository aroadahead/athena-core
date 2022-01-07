<?php

namespace AthenaCore\Mvc\Application\User\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use Poseidon\Data\DataObject;
use Traversable;

class UserManager extends ApplicationManager
{
    protected DataObject $jsLocalStorage;

    public function __construct()
    {
        $this -> jsLocalStorage = new DataObject();
    }

    public function getAllJsLocalStorageItems(): Traversable
    {
        return $this -> jsLocalStorage -> getIterator();
    }

    public function addJsLocalStorageItem(string $name, mixed $value): void
    {
        $this -> jsLocalStorage -> setItem($name, $value);
    }

    public function getJsLocalStorageItem(string $name): mixed
    {
        return $this -> jsLocalStorage -> getItem($name);
    }

    public function removeJsLocalStorageItem(string $name): void
    {
        $this -> jsLocalStorage -> removeItem($name);
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
}