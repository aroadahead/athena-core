<?php

declare(strict_types=1);

namespace AthenaCoreTest;

use AthenaCore\ConfigProvider;
use PHPUnit\Framework\TestCase;
use function array_walk;

/**
 * AthenaCore config provider test case
 */
class ConfigProviderTestCase extends TestCase
{
    /**
     * Test config provider invoke
     */
    public function testInvoke(): void
    {
        $config = (new ConfigProvider()) -> __invoke();

        $keys = ['view_manager', 'controllers', 'service_manager', 'router', 'translator', 'view_helpers'];
        array_walk($keys, function ($item) use ($config) {
            self ::assertArrayHasKey($item, $config, "Expected config to have {$item} array key.");
        });
    }
}