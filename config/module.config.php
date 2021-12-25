<?php

declare(strict_types=1);

use AthenaCore\Application\Controller\Factory\IndexControllerFactory;
use AthenaCore\Application\Controller\IndexController;

return [
    'athena-core' => ['version' => '0.0.1'],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'athena-core/index/index' => __DIR__ . '/../view/athena-core/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
    ],
    'controllers' => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
        ],
    ],
    'service_manager' => [],
    'router' => [
        'routes' => [
            'application' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'translator' => [],
    'view_helpers' => [],
];
