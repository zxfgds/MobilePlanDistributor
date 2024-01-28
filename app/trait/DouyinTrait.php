<?php

namespace app\trait;

trait DouyinTrait
{
    /**
     * @var string $url Douyin开放平台的基本网址
     */
    private string $url = 'https://open.douyin.com';

    /**
     * @var string $access_token 用户授权的access_token
     */
    protected string $access_token;

    /**
     * @var string $open_id 用户的唯一标识
     */
    protected string $open_id;

    /**
     * @var string $union_id 用户在应用下的唯一标识，仅限未绑定或通过手机号搜索用户时返回
     */
    protected string $union_id;

    /**
     * @var string $refresh_token 过期后获取新的access_token时需要的参数
     */
    protected string $refresh_token;

    /**
     * @var int $expires_in access_token过期时间，单位为秒
     */
    protected int $expires_in;

    /**
     * @var array $scope 用户授权的作用域，可以通过解析JWT(token中间部分)得到
     */
    protected array $scope = [];

    /**
     * @var string $token_type access_token的类型，目前只支持bearer
     */
    protected string $token_type;

    /**
     * @var string $path 请求路径
     */
    protected string $path;

    /**
     * 初始化应用程序并设置相关配置。
     *
     * @param string $type 应用程序类型
     * @param array $config 应用程序配置
     * @return self 返回当前实例
     */
    public function buildApp(string $type, array $config): self
    {
        // 初始化应用程序和相关配置
        $this->type = $type;
        $this->config = $config;

        // 更多的初始化操作...

        return $this;
    }
}