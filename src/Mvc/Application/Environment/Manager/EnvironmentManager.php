<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Application\Environment\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use AthenaCore\Mvc\Application\Environment\Manager\Exception\RequiredEnvNotFound;
use AthenaCore\Service\Front\JsLocalStorageTrait;
use Laminas\Config\Config;
use Poseidon\Data\DataObject;
use Symfony\Component\Dotenv\Dotenv;

class EnvironmentManager extends ApplicationManager
{
    protected float $versionNumber;
    protected string $versionName;
    protected Dotenv $dotenv;
    protected Config $config;

    use JsLocalStorageTrait;

    public function __construct()
    {
        $this -> jsLocalStorage = new DataObject();
        $this->dotenv = new Dotenv();
        $this->dotenv->usePutenv(true);
        $this->config = new Config([],false);
        $this->loadStartupConfigs();
    }

    public function getRedisConfig():Config
    {
        return $this->config->redis;
    }

    /**
     * @return float
     */
    public function getVersionNumber(): float
    {
        return $this -> versionNumber;
    }

    /**
     * @param float $versionNumber
     */
    public function setVersionNumber(float $versionNumber): void
    {
        $this -> versionNumber = $versionNumber;
    }

    /**
     * @return string
     */
    public function getVersionName(): string
    {
        return $this -> versionName;
    }

    /**
     * @param string $versionName
     */
    public function setVersionName(string $versionName): void
    {
        $this -> versionName = $versionName;
    }

    #[Pure] public function getOptionalEnv(string $key): string|null
    {
        $val = getenv($key, true);
        if($val !==false){
            return $val;
        }
        return null;
    }

    public function getRequiredEnv(string $key): string
    {
        $val = getenv($key,true);
        if(!$val){
            throw new RequiredEnvNotFound("Required env not found: $key");
        }
        return $val;
    }


    public function setup(): void
    {
        $envFile = $this->getApplicationCore()->getRootPath()
            .DIRECTORY_SEPARATOR.'athena.env';
        $this->dotenv->load($envFile);
    }

    public function init(): void
    {

    }

    public function boot(): void
    {
        // TODO: Implement boot() method.
    }

    private function loadStartupConfigs()
    {
        $file = $this->getApplicationCore()->getRootPath().DIRECTORY_SEPARATOR.'startup.configs.php';
        $this->config->merge(new Config(include_once $file));
    }
}