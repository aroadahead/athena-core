<?php

namespace AthenaCore\Mvc\Application\Laminas\Manager\Exception;

use Psr\Container\ContainerInterface;
use function vsprintf;

class ExceptionManager
{
    public function __construct(protected ContainerInterface $container)
    {

    }

    public function throwException(string $class, string $msg, array $args):void
    {
        $instance = $this->getExceptionInstance($class);
        throw $instance(vsprintf($this->translate($msg),$args));
    }

    private function translate(string $to):string
    {
        return $this->container->get('MvcTranslator')->translate($to);
    }

    private function getExceptionInstance(string $class):\Throwable
    {
        return new $class();
    }
}