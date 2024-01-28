<?php

namespace app\logic;

use app\model\Platform;
use app\service\Platform\PlatformService;
use Illuminate\Support\Arr;
use RedisException;


class PlatformLogic // extends BaseLogic
{


    /**
     * @throws RedisException
     */
    public static function getAuthUrl($redirectUrl = null, $scopes = [])
    {
        // 获取当前平台的 ID
        $platformId = PlatformLogic::getPlatformId();
        // 微信 QQ 抖音 支付宝 快手
        $service = static::getProvider();

        if (!empty($scopes)) $service->scopes($scopes);
        if (!empty($redirectUrl)) $service->redirect($redirectUrl);
        return $service->getAuthUrl($redirectUrl);

    }


    /**
     * 将代码转换为用户
     *
     * @param string $code 代码
     *
     * @return array 包含用户信息的关联数组
     * @throws RedisException
     */
    public static function codeToUser(string $code): array
    {
        // 获取服务提供者实例
        $service = static::getProvider();
        // 调用服务提供者的方法，将代码转换为用户名
        return $service->codeToUser($code);
    }



//  获取实例

    /**
     * 获取平台提供程序
     *
     * @throws RedisException
     */
    public static function getProvider()
    {
        // 获取平台信息
        $platformInfo = static::getPlatform();
        // 获取平台类型
        $typeName = $platformInfo['type'];
        // 如果平台类型是 mini， 则将typeName设置为 platformMini
        if ($platformInfo['type'] == "mini") $typeName = "platformMini";
        // 如果平台类型是 official，则将typeName设置为 platformH5
        if ($platformInfo['type'] == "official") {
            $typeName = "platformH5";
            // 抖音 调用 open
            if ($platformInfo['platform'] == "douyin") {
                $typeName = "platformOpen";
                $platformInfo['type'] = 'open';
            }
        }
        // 根据平台类型获取平台配置，并创建平台服务对象返回
        return (new PlatformService(SettingLogic::get($typeName)))->create($platformInfo['type'], $platformInfo['platform']);
    }


    /**
     * 获取平台ID
     *
     * 通过请求头中的 'params' 参数，解析出平台ID。
     *
     * @return int 平台ID
     */
    public static function getPlatformId(): int
    {
        // 从请求头中获取 'params' 参数
        $queryParams = request()->header('params');
        // 将 'params' 参数从 JSON 字符串解码为数组
        $params = jsonDecode($queryParams);
        // 从参数数组中提取 'platform_id'
        return Arr::pull($params, 'platform_id');
    }

    /**
     * 获取平台信息
     *
     * 通过请求头中的 'params' 参数，解析出平台ID，然后根据平台ID获取平台类型和平台名称。
     *
     * @return array 包含平台类型和平台名称的关联数组
     */
    public static function getPlatform(): array
    {
        // 从请求头中获取 'params' 参数
        $queryParams = request()->header('params');
        // 将 'params' 参数从 JSON 字符串解码为数组
        $params = jsonDecode($queryParams);
        // 从参数数组中提取 'platform_id'
        $platformId = Arr::pull($params, 'platform_id');
        // 使用平台ID获取平台类型
        $type = Platform::platformType($platformId);
        // 使用平台ID获取平台名称
        $platform = Platform::PLATFORM_DRIVERS[$platformId] ?? 'unknown';
        // 返回包含平台类型和平台名称的关联数组
        return ['type' => $type, 'platform' => $platform];
    }


    /**
     * @param $category
     *
     * @return mixed
     * @throws RedisException
     */
    public static function getConfig($category): mixed
    {
        return SettingLogic::get($category);
    }
}