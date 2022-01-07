<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Controller;

use AthenaCore\Mvc\Service\MvcService;
use Laminas\Mvc\Controller\AbstractActionController;
use Elephant\Reflection\ReflectionClass;
use Interop\Container\ContainerInterface;
use Laminas\Filter\Word\CamelCaseToDash;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

use function strpos;
use function strtolower;
use function substr;

/**
 * Abstract Mvc controller
 */
abstract class MvcController extends AbstractActionController
{
    /**
     * Root namespace
     */
    protected string $rootNamespace;

    /**
     * Camelcase to dash filter
     */
    protected static ?CamelCaseToDash $filter = null;

    /** @throws ReflectionException */
    public function __construct(protected ContainerInterface $container)
    {
        if (self::$filter === null) {
            self::$filter = new CamelCaseToDash();
        }

        $namespace           = (new ReflectionClass($this))->getNamespaceName();
        $namespace           = strtolower(substr($namespace, 0, strpos($namespace, '\\')));
        $this->rootNamespace = self::$filter->filter($namespace);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function invokeService(?string $module = null): mixed
    {
        if ($module === null) {
            $module = $this->rootNamespace;
        }

        return $this->container->get('services.moduleServiceLoader')->load($module);
    }
}
