<?php

namespace app\service\Platform;

use Illuminate\Support\Str;
use InvalidArgumentException;

class PlatformServiceBak
{
    
    protected array $config;
    
    
    public function __construct($config)
    {
        $this->config = $config;
    }
    
    /**
     * 创建 OAuth 提供程序的实例
     *
     * @param string $providerName 提供程序名称
     *
     * @return mixed 返回 OAuth 提供程序实例
     * @throws InvalidArgumentException 如果提供程序未找到时抛出异常
     */
    public function create(string $providerName): mixed
    {
        $providerClass = $this->getProviderClass($providerName);
        
        if (!class_exists($providerClass)) {
            throw new InvalidArgumentException("Provider '{$providerName}' not found.");
        }
        return new $providerClass($this->config);
    }
    
    
    /**
     * 获取第三方服务提供者类名
     *
     * @param string $providerName 第三方服务提供者名称
     *
     * @return string 第三方服务提供者类名
     */
    protected function getProviderClass(string $providerName): string
    {
        // 将 providerName 转换为驼峰命名法并将首字母大写
        $providerName = ucfirst(Str::camel($providerName));
        
        // 组装类名并返回
        return "app\\service\\Platform_back\\Providers\\{$providerName}";
    }
    
}