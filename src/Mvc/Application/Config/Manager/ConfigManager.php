<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Application\Config\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use AthenaCore\Mvc\Application\Config\Facade\Facade;
use AthenaCore\Mvc\Application\Config\Loader\DirectoryLoader;
use AthenaCore\Mvc\Application\Config\Loader\FileLoader;
use AthenaCore\Mvc\Application\Config\Lookup\NodeLookup;
use Exception;
use Laminas\Config\Config;
use Laminas\Json\Json;
use phpseclib3\Exception\FileNotFoundException;
use function array_walk;
use function is_dir;
use function is_file;

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

    public function facade(): Facade
    {
        return $this -> facade;
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

    public function load(string $path, array $excludeRootPaths = []): void
    {
        if (!is_file($path) && !is_dir($path)) {
            throw new FileNotFoundException("invalid file config path: $path");
        }
        if (is_file($path)) {
            $this -> merge($this -> fileLoader -> loadFile($path));
        } else {
            $files = $this -> directoryLoader -> load($path, $excludeRootPaths);
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
        $this -> facade = new Facade($this);
        $configDir = $this -> applicationCore -> getFilesystemManager()
            -> getDirectoryPaths() -> facade() -> config();
        $cache = $this -> applicationCore -> getCacheManager();
        if ($cache -> hasData('config.flush')) {
            $cache -> removeData('config');
            if (!$cache -> hasData('config')) {
                $cache -> removeData('config.flush');
            } else {
                throw new Exception("Error flushing config cache data.");
            }
        }
        if ($cache -> hasData('config')) {
            $arr = Json::decode($cache->getData('config'),Json::TYPE_ARRAY);
            $config = new Config($arr,false);
            $this -> merge($config);
        } else {
            $this -> load($configDir, ['laminas']);
            $json = Json::encode($this->masterConfig->toArray());
            $cache -> setData('config',$json);
        }
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