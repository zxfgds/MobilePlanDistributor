<?php

namespace app\trait;

use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\MiniApp\Application as MiniApp;
use EasyWeChat\OfficialAccount\Application as OfficialAccount;
use EasyWeChat\OpenPlatform\Application as OpenPlatform;
use EasyWeChat\Pay\Application as Pay;
use EasyWeChat\Pay\Server;
use EasyWeChat\Work\Application as Work;
use Illuminate\Support\Arr;
use ReflectionException;
use Throwable;

trait WechatTrait
{
    /**
     * @param $platformType
     * @param $config
     *
     * @return MiniApp|OfficialAccount|OpenPlatform|Pay|Work
     * @throws InvalidArgumentException
     */
    protected function buildApp($platformType, $config): MiniApp|Pay|Work|OpenPlatform|OfficialAccount
    {
        $config['app_id'] = Arr::pull($config, 'appid');
        $config['secret'] = Arr::pull($config, 'appsecret');
        $config['token'] = Arr::pull($config, 'token');
        $config['aes_key'] = Arr::pull($config, 'aesKey');
        return match ($platformType) {
            'official' => new OfficialAccount($config),
            'mini' => new MiniApp($config),
            'open' => new OpenPlatform($config),
            'pay' => new Pay($config),
            'work' => new Work($config),
            default => throw new InvalidArgumentException('不支持的平台类型'),
        };
    }
}