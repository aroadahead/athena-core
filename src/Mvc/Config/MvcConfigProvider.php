<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Config;

use Elephant\Reflection\ReflectionClass;
use Laminas\Filter\Word\CamelCaseToDash;
use ReflectionException;

/**
 * Abstract Mvc config provider
 */
abstract class MvcConfigProvider
{
    /**
     * Config provider namespace
     */
    protected string $formattedNamespace;

    /**
     * Camelcase to dash filter
     */
    protected static ?CamelCaseToDash $filter = null;

    /** @throws ReflectionException */
    public function __construct()
    {
        if (self::$filter === null) {
            self::$filter = new CamelCaseToDash();
        }

        $this->formattedNamespace = self::$filter->filter((new ReflectionClass($this))->getNamespaceName());
    }

    /** @return array[] */
    public function __invoke(): array
    {
        $moduleConfigPath = '';

        return include $moduleConfigPath . '/module.config.php';
    }
}
