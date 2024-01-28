<?php
return [
    'default' => [
        'host'     => getenv('REDIS_HOST'),
        'password' => getenv('REDIS_PASSWORD'),
        'port'     => getenv('REDIS_PORT'),
        'database' => getenv('REDIS_DATABASE'),
    ],
    'cache'   => [
        'host'     => getenv('REDIS_HOST'),
        'password' => getenv('REDIS_PASSWORD'),
        'port'     => getenv('REDIS_PORT'),
        'database' => getenv('REDIS_CACHE_DATABASE'),
    ],
    'queue'   => [
        'host'     => getenv('REDIS_HOST'),
        'port'     => getenv('REDIS_PORT'),
        'password' => getenv('REDIS_PASSWORD'),
        'database' => 3
    ],
];
