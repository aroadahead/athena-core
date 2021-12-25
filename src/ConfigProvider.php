<?php

declare(strict_types=1);

namespace AthenaCore;

use AthenaCore\Mvc\Config\MvcConfigProvider;

/**
 * Config provider for AthenaCore config
 */
class ConfigProvider extends MvcConfigProvider
{
    public function __invoke(): array
    {
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }
}