<?php

namespace AthenaCore\View\Helper\Js\Factory;

use AthenaCore\View\Helper\Js\JsLocalStorage;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class JsLocalStorageFactory implements FactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new JsLocalStorage($container);
    }
}