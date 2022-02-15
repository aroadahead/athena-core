<?php

namespace AthenaCore\Mvc\Application\Filesystem\Manager\Directory;

use AthenaCore\Mvc\Application\Filesystem\Manager\Directory\Facade\Facade;
use AthenaException\File\PathNotFoundException;
use Poseidon\Data\DataObject;
use function array_walk;
use function is_dir;
use function mkdir;
use function str_ireplace;

class DirectoryPaths extends DataObject
{
    protected ?Facade $facade = null;

    public function __construct(array $data = [])
    {
        parent ::__construct($data);
    }

    public function loadPaths(array $paths): void
    {
        array_walk($paths, function ($item, $key) {
            if (!is_dir($item['path'])) {
                if ($item['create']) {
                    mkdir($item['path'], $item['mode'], true);
                } else {
                    throw new PathNotFoundException("path {$item['path']} does not exist!");
                }
            }
            $this -> setItem($key, $item['path']);
        });
        $this -> facade = new Facade($this);
    }

    public function facade(): Facade
    {
        return $this -> facade;
    }

    public function getPath(string $name): string
    {
        return $this -> getOrFail($name);
    }

    public function __call(string $name, array $arguments)
    {
        $name = trim(str_ireplace('Path', '', $name));
        return parent ::__call($name, $arguments);
    }
}