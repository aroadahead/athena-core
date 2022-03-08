<?php
declare(strict_types=1);
namespace AthenaCore\Session;

use AthenaBridge\Laminas\Session\Container;

class AbstractSessionContainer extends Container
{
    public function set(string $key, mixed $value): void
    {
        $this -> offsetSet($key, $value);
    }

    public function get(string $key): mixed
    {
        return $this -> offsetGet($key);
    }

    public function remove(string $key): void
    {
        $this -> offsetUnset($key);
    }

    public function has(string $key): bool
    {
        return $this -> offsetExists($key);
    }
}