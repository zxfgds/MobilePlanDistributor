<?php

namespace app\service\Platform\Providers\Official;

use app\service\Platform\Traits\WechatTrait;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;

class Wechat extends Base
{

    use WechatTrait;

    // 使用 WechatTrait

    protected array $config;


    /**
     * 构造函数
     * @param array $config 配置数组
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * 获取授权url
     * @return string 返回授权url
     * @throws InvalidArgumentException 参数错误时抛出异常
     */
    function getAuthUrl(): string
    {
        $auth = $this->buildApp('official', $this->config)->getOAuth();
        return $auth->scopes($this->scopes ?? ['snsapi_base'])->redirect($this->redirectUrl);
    }

    function codeToUser(string $code)
    {
        // TODO: Implement codeToUser() method.
    }
}