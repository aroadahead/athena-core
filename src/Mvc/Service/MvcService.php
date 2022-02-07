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

    public function hello():string
    {
        return 'hello';
    }
}