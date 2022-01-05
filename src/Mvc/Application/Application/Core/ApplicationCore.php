<?php

namespace AthenaCore\Mvc\Application\Application\Core;

use AthenaCore\Mvc\Application\Api\Manager\ApiManager;
use AthenaCore\Mvc\Application\Application\Core\Exception\InvalidManager;
use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use AthenaCore\Mvc\Application\Cache\Manager\CacheManager;
use AthenaCore\Mvc\Application\Config\Manager\ConfigManager;
use AthenaCore\Mvc\Application\Db\Manager\DbManager;
use AthenaCore\Mvc\Application\Design\Manager\DesignManager;
use AthenaCore\Mvc\Application\Environment\Manager\EnvironmentManager;
use AthenaCore\Mvc\Application\Filesystem\Manager\FilesystemManager;
use AthenaCore\Mvc\Application\Laminas\Manager\LaminasManager;
use AthenaCore\Mvc\Application\Log\Manager\LogManager;
use AthenaCore\Mvc\Application\User\Manager\UserManager;
use JetBrains\PhpStorm\Pure;
use function array_walk;

abstract class ApplicationCore
{
    protected LogManager $logManager;
    protected CacheManager $cacheManager;
    protected ConfigManager $configManager;
    protected DbManager $dbManager;
    protected DesignManager $designManager;
    protected FilesystemManager $filesystemManager;
    protected EnvironmentManager $environmentManager;
    protected UserManager $userManager;
    protected LaminasManager $laminasManager;
    protected ApiManager $apiManager;
    protected array $managers = [];

    #[Pure] public function __construct()
    {
        $this -> logManager = new LogManager();
        $this -> cacheManager = new CacheManager();
        $this -> configManager = new ConfigManager();
        $this -> dbManager = new DbManager();
        $this -> designManager = new DesignManager();
        $this -> filesystemManager = new FilesystemManager();
        $this -> environmentManager = new EnvironmentManager();
        $this -> userManager = new UserManager();
        $this -> laminasManager = new LaminasManager();
        $this -> apiManager = new ApiManager();
        $this -> managers = ManagerManifest ::getManagerManifest();
    }

    abstract public function deploy();

    public function setCores(): self
    {
        array_walk($this -> managers, function ($item) {
            $manager = $item . 'Manager';
            if ($this -> $manager instanceof ApplicationManager) {
                $this -> $manager -> setApplicationCore($this);
            }
        });
        return $this;
    }

    /**
     * @throws InvalidManager
     */
    public function setup(): self
    {
        $this -> setupManagers();
        return $this;
    }

    /**
     * @throws InvalidManager
     */
    public function init(): self
    {
        $this -> initManagers();
        return $this;
    }

    /**
     * @throws InvalidManager
     */
    public function boot(): self
    {
        $this -> bootManagers();
        return $this;
    }

    /**
     * @throws InvalidManager
     */
    protected function setupManagers(): void
    {
        array_walk($this -> managers, function ($item) {
            $manager = $item . 'Manager';
            if ($this -> $manager instanceof ApplicationManager) {
                $this -> $manager -> setup();
            } else {
                throw new InvalidManager("Invalid manager: $manager");
            }
        });
    }

    /**
     * @throws InvalidManager
     */
    protected function initManagers(): void
    {
        array_walk($this -> managers, function ($item) {
            $manager = $item . 'Manager';
            if ($this -> $manager instanceof ApplicationManager) {
                $this -> $manager -> init();
            } else {
                throw new InvalidManager("Invalid manager: $manager");
            }
        });
    }

    /**
     * @throws InvalidManager
     */
    protected function bootManagers(): void
    {
        array_walk($this -> managers, function ($item) {
            $manager = $item . 'Manager';
            if ($this -> $manager instanceof ApplicationManager) {
                $this -> $manager -> boot();
            } else {
                throw new InvalidManager("Invalid manager: $manager");
            }
        });
    }

    /**
     * @return string
     */
    #[Pure] public function getRootPath(): string
    {
        return $this -> filesystemManager -> getRootPath();
    }

    /**
     * @param string $rootPath
     */
    public function setRootPath(string $rootPath): void
    {
        $this -> filesystemManager -> setRootPath($rootPath);
    }

    /**
     * @return ApiManager
     */
    public function getApiManager(): ApiManager
    {
        return $this -> apiManager;
    }

    /**
     * @return LogManager
     */
    public function getLogManager(): LogManager
    {
        return $this -> logManager;
    }

    /**
     * @return CacheManager
     */
    public function getCacheManager(): CacheManager
    {
        return $this -> cacheManager;
    }

    /**
     * @return ConfigManager
     */
    public function getConfigManager(): ConfigManager
    {
        return $this -> configManager;
    }

    /**
     * @return DbManager
     */
    public function getDbManager(): DbManager
    {
        return $this -> dbManager;
    }

    /**
     * @return DesignManager
     */
    public function getDesignManager(): DesignManager
    {
        return $this -> designManager;
    }

    /**
     * @return FilesystemManager
     */
    public function getFilesystemManager(): FilesystemManager
    {
        return $this -> filesystemManager;
    }

    /**
     * @return EnvironmentManager
     */
    public function getEnvironmentManager(): EnvironmentManager
    {
        return $this -> environmentManager;
    }

    /**
     * @return UserManager
     */
    public function getUserManager(): UserManager
    {
        return $this -> userManager;
    }

    /**
     * @return LaminasManager
     */
    public function getLaminasManager(): LaminasManager
    {
        return $this -> laminasManager;
    }
}