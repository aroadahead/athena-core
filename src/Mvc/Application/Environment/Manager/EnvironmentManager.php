<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Application\Environment\Manager;

use AthenaCore\Mvc\Application\Application\Manager\ApplicationManager;
use AthenaCore\Service\Front\JsLocalStorageTrait;
use Poseidon\Data\DataObject;

class EnvironmentManager extends ApplicationManager
{
    protected float $versionNumber;
    protected string $versionName;

    use JsLocalStorageTrait;

    public function __construct()
    {
        $this -> jsLocalStorage = new DataObject();
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


    public function setup(): void
    {
        // TODO: Implement setup() method.
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