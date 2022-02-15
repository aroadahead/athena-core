<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Application\Config\Lookup;

use Poseidon\Data\DataObject;

class ConfigCache extends DataObject
{
    public function configExists(string $node): bool
    {
        return $this -> hasItem($node);
    }

    public function configGet(string $node): mixed
    {
        return $this -> getItem($node);
    }

    public function configStore(string $node, mixed $data): void
    {
        $this -> setItem($node, $data);
    }
}