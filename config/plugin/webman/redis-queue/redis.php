<?php
return [
    'default' => [
        'host'    => "redis://" . config('redis.queue.host') . ":" . config('redis.queue.port'),
        'options' => [
            'auth'          => config('redis.queue.password'),       // 密码，字符串类型，可选参数
            'db'            => config('redis.queue.database'),            // 数据库
            'prefix'        => '',       // key 前缀
            'max_attempts'  => 0, // 消费失败后，重试次数
            'retry_seconds' => 5, // 重试间隔，单位秒
        ],
    
    ],
];
