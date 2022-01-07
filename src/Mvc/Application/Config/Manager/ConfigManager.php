<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Application\Config\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use AthenaCore\Mvc\Application\Config\Facade\Facade;
use AthenaCore\Mvc\Application\Config\Loader\DirectoryLoader;
use AthenaCore\Mvc\Application\Config\Loader\FileLoader;
use AthenaCore\Mvc\Application\Config\Lookup\NodeLookup;
use Laminas\Config\Config;
use phpseclib3\Exception\FileNotFoundException;
use function array_walk;
use function is_dir;
use function is_file;
use function var_dump;

class ConfigManager extends ApplicationManager
{
    private FileLoader $fileLoader;
    private DirectoryLoader $directoryLoader;
    private Config $masterConfig;
    private NodeLookup $nodeLookup;
    private Facade $facade;

    public function __construct()
    {
        $this -> fileLoader = new FileLoader();
        $this -> directoryLoader = new DirectoryLoader();
        $this -> masterConfig = new Config([], true);
        $this -> nodeLookup = new NodeLookup($this -> masterConfig);
    }

    public function facade():Facade
    {
        return $this->facade;
    }

    public function set(string $node, mixed $data): void
    {
        $this -> nodeLookup -> set($node, $data);
    }

    public function lookup(string $node = null, bool $asArray = false)
    {
        $data = $this -> nodeLookup -> descend($node);
        if ($asArray && ($data instanceof Config)) {
            return $data -> toArray();
        }
        if ($asArray) {
            return [$data];
        }
        return $data;
    }

    public function load(string $path): void
    {
        if (!is_file($path) && !is_dir($path)) {
            throw new FileNotFoundException("invalid file config path: $path");
        }
        if (is_file($path)) {
            $this -> merge($this -> fileLoader -> loadFile($path));
        } else {
            $files = $this -> directoryLoader -> load($path);
            array_walk($files, function ($item) {
                $this -> merge($this -> fileLoader -> loadFile($item));
            });
        }
    }

    public function merge(Config $config): void
    {
        $this -> masterConfig -> merge($config);
    }

    public function setup(): void
    {
        $this->facade = new Facade($this);
        $configDir = $this->applicationCore->getFilesystemManager()
            ->getDirectoryPaths()->facade()->configProject();
        $this->load($configDir);
        var_dump($this->lookup('design.config.html.title'));
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