<?php

namespace app\service\Platform;

use Closure;
use InvalidArgumentException;

/**
 * @method codeToUser($code);
 */
class PlatformService
{
    protected array $config; // 存储配置信息的数组

    protected array $resolved = []; // 存储已解析过的实例的数组
    protected static array $customCreators = []; // 存储自定义的创建器的静态数组
    protected const PROVIDERS = [ // 支持的服务提供者数组
        'ecommerce' => [
            'douyin' => Providers\Ecommerce\Douyin::class, // 抖音电商服务提供者类
            'kuaishou' => Providers\Ecommerce\Kuaishou::class, // 快手电商服务提供者类
            'xiaohongshu' => Providers\Ecommerce\Xiaohongshu::class, // 小红书电商服务提供者类
        ],
        'open' => [
            'douyin' => Providers\Open\Douyin::class, // 抖音开放平台服务提供者类
            'kuaishou' => Providers\Open\Kuaishou::class, // 快手开放平台服务提供者类
            'wechat' => Providers\Open\Wechat::class, // 微信开放平台服务提供者类
            'alipay' => Providers\Open\Alipay::class, // 支付宝开放平台服务提供者类
        ],
        'mini' => [
            'douyin' => Providers\Mini\Douyin::class, // 抖音小程序服务提供者类
            'kuaishou' => Providers\Mini\Kuaishou::class, // 快手小程序服务提供者类
            'wechat' => Providers\Mini\Wechat::class, // 微信小程序服务提供者类
            'alipay' => Providers\Mini\Alipay::class, // 支付宝小程序服务提供者类
            'quick' => Providers\Mini\Quick::class, // 快应用服务提供者类
            'quick-huawei' => Providers\Mini\QuickHuawei::class, // 快应用华为版服务提供者类
            'qq' => Providers\Mini\Qq::class, // QQ 小程序服务提供者类
        ],
        'official' => [
            'wechat' => Providers\Official\Wechat::class, // 微信公众号服务提供者类
            'alipay' => Providers\Official\Alipay::class, // 支付宝生活号服务提供者类
            'douyin' => Providers\Official\Douyin::class, // 抖音生活号服务提供者类(导航到开放平台)
        ],
    ];

    /**
     * 构造函数，用于初始化配置
     *
     * @param array $config 配置
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * 创建Provider实例
     *
     * @param string $type Provider类型
     * @param string $platform 平台
     *
     *
     * @throws InvalidArgumentException 当不支持指定的Provider类型和平台时抛出异常
     */
    public function create(string $type, string $platform)
    {

        $key = "{$type}.{$platform}";

        if (!isset($this->resolved[$key])) {
            $this->resolved[$key] = $this->createProvider($type, $platform);
        }

        return $this->resolved[$key];
    }

    /**
     * 创建Provider实例
     *
     * @param string $type Provider类型  小程序 公众号  第三方开放平台  电商平台
     * @param string $platform 平台 支付宝 微信 ...
     *
     *
     * @throws InvalidArgumentException 当不支持指定的Provider类型和平台时抛出异常
     */
    protected function createProvider(string $type, string $platform, $factory = null)
    {
        if (!isset(self::PROVIDERS[$type][$platform])) {
            throw new InvalidArgumentException("Provider [{$type}/{$platform}] not supported.");
        }
        $providerClass = self::PROVIDERS[$type][$platform];
        $config = $this->config[$platform] ?? [];
        return new $providerClass($config, $factory);
    }

    /**
     * 扩展Provider
     *
     * @param string $type Provider类型
     * @param string $platform 平台
     * @param Closure $callback 回调函数
     *
     * @return $this
     */
    public function extend(string $type, string $platform, Closure $callback): self
    {
        self::$customCreators["{$type}.{$platform}"] = $callback;

        return $this;
    }

    /**
     * 获取已解析的Provider
     *
     * @return array 已解析的Provider数组
     */
    public function getResolvedProviders(): array
    {
        return $this->resolved;
    }

}
