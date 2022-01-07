<?php

namespace AthenaCore\View\Helper;

use Poseidon\Data\DataObject;
use Traversable;

trait JsLocalStorageTrait
{
    protected DataObject $jsLocalStorage;

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
}