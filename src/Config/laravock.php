<?php

return [
    'version' => '3',

    'redis'   => [
        'enabled' => true,
    ],
    'mysql'   => [
        'enabled' => true,
        'params'  => [
            'rootpass' => 'laravocker',
            'database' => 'laravocker',
            'username' => 'laravocker',
            'password' => 'laravocker',
        ],
    ],
    'horizon' => [
        'enabled' => true,
    ],
    'cron'    => [
        'enabled' => true,
    ],
];