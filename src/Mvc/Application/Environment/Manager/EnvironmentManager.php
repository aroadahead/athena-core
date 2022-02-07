<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Application\Environment\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use AthenaCore\Mvc\Application\Environment\Manager\Exception\RequiredEnvNotFound;
use AthenaCore\Service\Front\JsLocalStorageTrait;
use Laminas\Config\Config;
use Poseidon\Data\DataObject;
use Symfony\Component\Dotenv\Dotenv;
use function ini_set;

class EnvironmentManager extends ApplicationManager
{
    protected Dotenv $dotenv;
    protected Config $config;
    public const DEVELOPMENT = 'development';
    public const STAGING = 'staging';
    public const PRODUCTION = 'production';
    public const UAT = 'uat';
    public const PSR = 'psr';

    use JsLocalStorageTrait;

    public function __construct()
    {
        $this -> jsLocalStorage = new DataObject();
        $this -> dotenv = new Dotenv();
        $this -> dotenv -> usePutenv(true);
        $this -> config = new Config([], false);
    }

    public function getDefaultLocale(): string
    {
        return $this -> config -> defaultLocale;
    }

    public function getRedisConfig(): Config
    {
        return $this -> config -> redis;
    }

    /**
     * @return float
     */
    public function getVersionNumber(): string
    {
        return $this -> config -> versionNumber;
    }

    /**
     * @return string
     */
    public function getVersionName(): string
    {
        return $this -> config -> versionName;
    }

    #[Pure] public function getOptionalEnv(string $key): string|null
    {
        $val = getenv($key, true);
        if ($val !== false) {
            return $val;
        }
        return null;
    }

    public function getRequiredEnv(string $key): string
    {
        $val = getenv($key, true);
        if (!$val) {
            throw new RequiredEnvNotFound("Required env not found: $key");
        }
        return $val;
    }

    public function getEnvironment(): string
    {
        return $this -> config -> environment;
    }

    public function isDevelopmentEnvironment(): bool
    {
        return ($this -> getEnvironment() === self::DEVELOPMENT);
    }

    public function isStagingEnvironment(): bool
    {
        return ($this -> getEnvironment() === self::STAGING);
    }

    public function isProductionEnvironment(): bool
    {
        return ($this -> getEnvironment() === self::PRODUCTION);
    }

    public function isUatEnvironment(): bool
    {
        return ($this -> getEnvironment() === self::UAT);
    }

    public function isPsrEnvironment(): bool
    {
        return ($this -> getEnvironment() === self::PSR);
    }

    public function getPaths(): array
    {
        return $this -> config -> paths -> toArray();
    }


    public function setup(): void
    {
        $envFile = $this -> getApplicationCore() -> getRootPath()
            . DIRECTORY_SEPARATOR . 'athena.env';
        $this -> dotenv -> load($envFile);
        $this -> loadStartupConfigs();
        if(isset($this->config->inis)){
            foreach ($this -> config -> inis as $ini => $val) {
                ini_set($ini, $val);
            }
        }
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
        $file = $this -> getApplicationCore() -> getRootPath() . DIRECTORY_SEPARATOR . 'startup.configs.php';
        $this -> config -> merge(new Config(include_once $file));
    }
}