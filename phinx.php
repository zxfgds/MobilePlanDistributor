<?php


return
    [
        'paths'         => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/database/migrations',
            'seeds'      => '%%PHINX_CONFIG_DIR%%/database/seeds',
        ],
        'environments'  => [
            'default_migration_table' => 'phinx_log',
            'default_environment'     => 'development',
            'production'              => [
                'adapter' => getenv('DB_CONNECTION'),
                'host'    => getenv('DB_HOST'),
                'name'    => getenv('DB_DATABASE'),
                'user'    => getenv('DB_USERNAME'),
                'pass'    => getenv('DB_PASSWORD'),
                'port'    => getenv('DB_PORT'),
                'charset' => getenv('DB_CHARSET'),
            ],
            'development'             => [
                'adapter' => "mysql",
                'host'    => "127.0.0.1",
                'name'    => "haoka",
                'user'    => "haoka",
                'pass'    => "WRcswGP2NF4LTyYM",
                'port'    => 3306,
                'charset' => "utf8mb4",
            ],
            'testing'                 => [
                'adapter' => getenv('DB_CONNECTION'),
                'host'    => getenv('DB_HOST'),
                'name'    => getenv('DB_DATABASE'),
                'user'    => getenv('DB_USERNAME'),
                'pass'    => getenv('DB_PASSWORD'),
                'port'    => getenv('DB_PORT'),
                'charset' => getenv('DB_CHARSET'),
            ],
        ],
        'version_order' => 'creation',
    ];
