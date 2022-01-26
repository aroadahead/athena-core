<?php

declare(strict_types=1);

use AthenaCore\Mvc\Service\Listener\Factory\CoreListenerFactory;
use AthenaCore\View\Helper\Js\Factory\JsLocalStorageFactory;
use AthenaCore\View\Helper\Js\JsLocalStorage;
use AthenaCore\View\Helper\Mvc\Config;
use AthenaCore\View\Helper\Mvc\Factory\ConfigFactory;

return [
    'view_manager' => [],
    'controllers' => [],
    'service_manager' => [
        'factories' => [
            'coreListener' => CoreListenerFactory::class
        ]
    ],
    'router' => [],
    'translator' => [],
    'view_helpers' => [
        'factories' => [
            JsLocalStorage::class => JsLocalStorageFactory::class,
            Config::class => ConfigFactory::class
        ],
        'aliases' => [
            'jsLocalStorage' => JsLocalStorage::class,
            'config' => Config::class
        ]
    ],
];
