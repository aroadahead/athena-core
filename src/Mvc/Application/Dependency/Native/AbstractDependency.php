<?php

namespace AthenaCore\Mvc\Application\Dependency\Native;

use AthenaCore\Mvc\Application\Application\Core\ApplicationCore;
use Laminas\Config\Config;

abstract class AbstractDependency
{
    /**
     * Priority
     *
     * @var int
     */
    protected int $priority;

    /**
     * Name
     *
     * @var string
     */
    protected string $name;

    /**
     * Enabled
     *
     * @var bool
     */
    protected bool $enabled;

    /**
     * Config Data
     *
     * @var Config
     */
    protected Config $config;

    protected ?ApplicationCore $applicationContainer;

    /**
     * Return Application Container
     *
     * @return ApplicationCore|null
     */
    public function getApplicationCore(): ?ApplicationCore
    {
        return $this -> applicationContainer;
    }

    /**
     * Set Application Container
     *
     * @param ApplicationCore|null $applicationContainer
     * @return AbstractDependency
     */
    public function setApplicationCore(?ApplicationCore $applicationContainer): AbstractDependency
    {
        $this -> applicationContainer = $applicationContainer;
        return $this;
    }

    /**
     * Is Enabled?
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this -> enabled;
    }

    /**
     * Set Config Data
     *
     * @param Config $config
     * @return void
     */
    public function setConfig(Config $config): void
    {
        $this -> config = $config;
    }

    /**
     * Return Config Data
     *
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this -> config;
    }

    /**
     * Set Enabled
     *
     * @param bool $enabled
     * @return AbstractDependency
     */
    public function setEnabled(bool $enabled): AbstractDependency
    {
        $this -> enabled = $enabled;
        return $this;
    }

    /**
     * Return Name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this -> name;
    }

    /**
     * Set Name
     *
     * @param string $name
     * @return AbstractDependency
     */
    public function setName(string $name): AbstractDependency
    {
        $this -> name = $name;
        return $this;
    }

    /**
     * Set Priority
     *
     * @param int $priority
     */
    public function setPriority(int $priority): void
    {
        $this -> priority = $priority;
    }

    /**
     * Return Priority
     *
     * @return int
     */
    public function getPriority(): int
    {
        return $this -> priority;
    }

}