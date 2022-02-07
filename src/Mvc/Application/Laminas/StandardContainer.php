<?php

namespace AthenaCore\Mvc\Application\Laminas;

use Psr\Container\ContainerInterface;

class StandardContainer
{
    protected static ContainerInterface $container;

    public static function setPsrContainer(ContainerInterface $container):void
    {
        self::$container = $container;
    }

    public static function getPsrContainer():ContainerInterface
    {
        return self::$container;
    }
}