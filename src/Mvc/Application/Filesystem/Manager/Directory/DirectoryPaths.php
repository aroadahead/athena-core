<?php

namespace AthenaCore\Mvc\Application\Filesystem\Manager\Directory;

use AthenaCore\Mvc\Application\Filesystem\Manager\Directory\Exception\PathNotExists;
use AthenaCore\Mvc\Application\Filesystem\Manager\Directory\Facade\Facade;
use function array_walk;
use function chgrp;
use function chown;
use function is_dir;
use function mkdir;
use function shell_exec;
use function str_ireplace;

class DirectoryPaths extends \Poseidon\Data\DataObject
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
                    mkdir($item['path'], $item['mode']);
                    $out=[];
                    $ret=0;
                    exec("sudo bash -c 'chown {$item['owner']}:${item['group']} {$item['path']}'",$out,$ret);
                } else {
                    throw new \Exception("path {$item['path']} does not exist!");
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
        if ($this -> hasItem($name)) {
            return $this -> getItem($name);
        }
        throw new PathNotExists("$name is not a valid path key.");
    }

    public function __call(string $name, array $arguments)
    {
        $name = trim(str_ireplace('Path', '', $name));
        return parent ::__call($name, $arguments);
    }
}