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
            'name' => 'DeleteFooKey',
            'enabled' => false,
            'args' => [
                'key' => 'foo'
            ],
            'priority' => 100
        ],
        [
            'service' => 'redisDeleteCommand',
            'name' => 'DeleteBarKey',
            'enabled' => false,
            'args' => [
                'key' => 'bar'
            ],
            'priority' => 10
        ]
    ]
];