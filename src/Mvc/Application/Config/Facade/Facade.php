<?php

namespace AthenaCore\Mvc\Application\Config\Facade;

use AthenaCore\Mvc\Application\Application\Core\AbstractFacadeManager;
use AthenaCore\Mvc\Application\Config\Exception\NodeNotFound;
use AthenaCore\Mvc\Application\Config\Manager\ConfigManager;
use AthenaCore\Service\Front\JsLocalStorageTrait;
use Laminas\Config\Config;
use Poseidon\Data\DataObject;

class Facade extends AbstractFacadeManager
{
    use JsLocalStorageTrait;

    /**
     * Design Config Node
     *
     * @var string
     */
    private const DESIGN_CONFIG_NODE = "design";

    /**
     * Apis Config Node
     *
     * @var string
     */
    private const APIS_CONFIG_NODE = "apis";

    /**
     * Project Config Node
     *
     * @var string
     */
    private const PROJECT_CONFIG_NODE = "project";

    /**
     * Standard Application Config Node
     *
     * @var string
     */
    private const APPLICATION_CONFIG = "application";

    /**
     * Routes Config Node
     *
     * @var string
     */
    private const ROUTES_CONFIG_NODE = "routes";

    public function __construct(protected ConfigManager $configManager)
    {
        $this -> jsLocalStorage = new DataObject();
    }

    /**
     * Returns Routes Configs.
     *
     * @param string $module
     * @return Config
     * @throws NodeNotFound
     */
    public function getRoutesConfig(string $module): Config
    {
        return $this -> getApplicationConfig(static::ROUTES_CONFIG_NODE . ".{$module}");
    }

    /**
     * Returns Design Configs.
     *
     * @param string|null $node
     * @return mixed
     * @throws NodeNotFound
     */
    public function getDesignConfig(string $node = null): mixed
    {
        return $this -> configData($this -> parseNode(static::DESIGN_CONFIG_NODE, $node));
    }

    /**
     * Returns Apis Configs.
     *
     * @param string|null $node
     * @return mixed
     * @throws NodeNotFound
     */
    public function getApisConfig(string $node = null): mixed
    {
        return $this -> getProjectConfig($this -> parseNode(static::APIS_CONFIG_NODE, $node));
    }

    /**
     * Returns Project Configs.
     *
     * @param string|null $node
     * @return mixed
     * @throws NodeNotFound
     */
    public function getProjectConfig(string $node = null): mixed
    {
        return $this -> configData($this -> parseNode(static::PROJECT_CONFIG_NODE, $node));
    }

    /**
     * Returns Standard Application Configs.
     *
     * @param string|null $node
     * @return mixed
     * @throws NodeNotFound
     */
    public function getApplicationConfig(string $node = null): mixed
    {
        return $this -> configData($this -> parseNode(static::APPLICATION_CONFIG, $node));
    }

    public function getI18nConfig(string $node = null): mixed
    {
        return $this -> configData($this -> parseNode('i18n', $node));
    }

    /**
     * Returns Config Data As Array.
     *
     * @param string|null $node
     * @return array
     * @throws NodeNotFound
     */
    public function configDataAsArray(string $node = null): array
    {
        return $this -> configManager -> lookup($node, true);
    }

    /**
     * Returns Config Data.
     *
     * @param string|null $node
     * @return mixed
     * @throws NodeNotFound
     */
    public function configData(string $node = null): mixed
    {
        return $this -> configManager -> lookup($node);
    }

    /**
     * Parses Config Node.
     *
     * @param string $base
     * @param string|null $node
     * @return string
     */
    private function parseNode(string $base, string $node = null): string
    {
        return $base . (!is_null($node) ? ".{$node}" : "");
    }
}