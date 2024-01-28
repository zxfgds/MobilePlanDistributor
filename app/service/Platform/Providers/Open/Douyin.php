<?php

namespace app\service\Platform\Providers\Open;

use app\service\Platform\Traits\DouyinTrait;
use RedisException;

class Douyin extends Base
{
    use DouyinTrait;

    protected array $config = [];

    /**
     * 构造函数
     * @param array $config 配置数组
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     * @throws RedisException
     */
    function getAuthUrl(): string
    {
        $url = 'https://open.douyin.com/platform/oauth/connect';
//        https://open.douyin.com/platform/oauth/connect?client_key=CLIENT_KEY&response_type=code&scope=SCOPE&redirect_uri=REDIRECT_URI&state=STATE
        $queryParams = [
            'client_key' => $this->config['clientKey'],
            'response_type' => 'code',
            'scope' => 'user_base',
            'redirect_uri' => $this->redirectUrl,
            'state' => $this->state ?? $this->createState(),
        ];
        return $url . '?' . http_build_query($queryParams);
    }

    function codeToUser(string $code)
    {
        // TODO: Implement codeToUser() method.
    }
}