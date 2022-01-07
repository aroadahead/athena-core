<?php

declare(strict_types=1);

namespace AthenaCore\Mvc\Service;

/**
 * Abstract Mvc service
 */
abstract class MvcService
{
    public function hello():string
    {
        return 'hello';
    }
}