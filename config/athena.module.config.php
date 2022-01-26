<?php
return [
    'module' => ['version' => '0.0.1'],
    'listeners' => [
        ['service'=>'coreListener','enabled'=>true,'priority'=>10000]
    ],
    'commands' => [
        [
            'service' => 'redisDeleteCommand',
            'enabled' => true,
            'args' => [
                'key' => 'AthenaCore_AthenaModuleConfig'
            ]
        ]
    ]
];