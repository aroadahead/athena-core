<?php
return [
    'version' => '0.0.1',
    'author' => 'jrk',
    'listeners' => [
        ['service'=>'coreListener','enabled'=>true,'priority'=>10000]
    ],
    'commands' => [
        [
            'service' => 'redisDeleteCommand',
            'enabled' => false,
            'args' => [
                'key' => 'AthenaCore_AthenaModuleConfig'
            ]
        ]
    ]
];