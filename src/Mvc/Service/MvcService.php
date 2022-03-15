<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Service;

use Psr\Container\ContainerInterface;

/**
 * Abstract Mvc service
 */
abstract class MvcService
{
    public function __construct(protected ContainerInterface $container)
    {
    }

    public function throwException(string $class, string $msg, array $args = []): void
    {
        $this -> container -> get('laminas') -> getExceptionManager()
            -> throwException($class, $msg, $args);
    }

    public function hello(): string
    {
        return 'hello';
    }
}