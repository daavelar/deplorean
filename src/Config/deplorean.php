<?php

return [
    'version' => '3',

    'redis'   => [
        'enabled' => true,
    ],
    'mysql'   => [
        'enabled' => true,
        'params'  => [
            'rootpass' => 'deplorean',
            'database' => 'deplorean',
            'username' => 'deplorean',
            'password' => 'deplorean',
        ],
    ],
    'horizon' => [
        'enabled' => true,
    ],
    'cron'    => [
        'enabled' => true,
    ],
];