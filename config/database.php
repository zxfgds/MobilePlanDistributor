<?php

return [
    "default"     => getenv('DB_CONNECTION'),
    "connections" => [
        "mysql" => [
            'driver'      => 'mysql',
            'host'        => getenv("DB_HOST",),
            'port'        => getenv("DB_PORT"),
            'database'    => getenv("DB_DATABASE"),
            'username'    => getenv('DB_USERNAME'),
            'password'    => getenv("DB_PASSWORD"),
            'unix_socket' => '',
            'charset'     => getenv('DB_CHARSET'),
            'collation'   => 'utf8mb4_unicode_ci',
            'prefix'      => getenv("DB_PREFIX"),
            'strict'      => TRUE,
            'engine'      => NULL,
        ],
    ],
];
