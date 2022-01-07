<?php

declare(strict_types=1);

use AthenaCore\View\Helper\Factory\JsLocalStorageFactory;
use AthenaCore\View\Helper\JsLocalStorage;
use Poseidon\Poseidon;

return [
    'athena-core' => ['version' => '0.0.1'],
    'view_manager' => [],
    'controllers' => [],
    'service_manager' => [
        'factories' => [
            'core' => function(){
                return Poseidon::getCore();
            }
        ]
    ],
    'router' => [],
    'translator' => [],
    'view_helpers' => [
        'factories' => [
            JsLocalStorage::class => JsLocalStorageFactory::class
        ],
        'aliases' => [
            'jsLocalStorage' => JsLocalStorage::class
        ]
    ],
];
