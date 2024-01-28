<?php

namespace app\service\Sms;

class SmsService
{

    protected array $config; // 存储配置信息的数组

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param $driver
     * @return mixed
     */
    public function create($driver): mixed
    {
        $driver = ucfirst($driver);
        $class = __NAMESPACE__ . "\\Driver\\{$driver}Driver";
        if (!class_exists($class)) {
            throw new \InvalidArgumentException("Driver [{$driver}] not supported.");
        }
        $config = $this->config[$driver] ?? [];
        return new $class($config);
    }


}