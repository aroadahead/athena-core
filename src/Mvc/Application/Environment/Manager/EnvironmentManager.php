<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Application\Environment\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use AthenaCore\Mvc\Application\Environment\Manager\Exception\RequiredEnvNotFound;
use AthenaCore\Service\Front\JsLocalStorageTrait;
use Poseidon\Data\DataObject;
use Symfony\Component\Dotenv\Dotenv;

class EnvironmentManager extends ApplicationManager
{
    protected float $versionNumber;
    protected string $versionName;
    protected Dotenv $dotenv;

    use JsLocalStorageTrait;

    public function __construct()
    {
        $this -> jsLocalStorage = new DataObject();
        $this->dotenv = new Dotenv();
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
        // TODO: Implement setup() method.
    }

    public function init(): void
    {
        $envFile = $this->applicationCore->getFilesystemManager()
            ->getDirectoryPaths()->facade()->root()
            .DIRECTORY_SEPARATOR.'athena.env';
        $this->dotenv->load($envFile);
    }

    public function boot(): void
    {
        // TODO: Implement boot() method.
    }
}