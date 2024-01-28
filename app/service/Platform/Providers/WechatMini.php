<?php

namespace app\service\Platform\Providers;

use app\service\Platform\User;
use app\service\Platform\Contracts;
use EasyWeChat\Kernel\Exceptions\HttpException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\MiniApp\Application;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;


class WechatMini extends Base
{
    public const name = 'wechat_mini'; // 微信小程序名称常量
    
    private Application $app; // 应用实例
    
    /**
     * 构造函数
     *
     * @param array $config 配置数组
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $config)
    {
        parent::__construct($config); // 调用父类构造函数
        
        $this->app = new Application($this->config); // 创建应用实例
    }
    
    /**
     * 获取用户信息
     *
     * @param string $code 用户登录凭证
     *
     * @return Contracts\UserInterface 用户实例
     * @throws HttpException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function userFromCode(string $code): Contracts\UserInterface
    {
        $utils = $this->app->getUtils(); // 获取工具实例
        $user  = $utils->codeToSession($code); // 获取用户信息
        
        return new User([
            Contracts\ABNF_ID       => $user['openid'] ?? NULL, // 用户唯一标识
            Contracts\ABNF_NAME     => $user[Contracts\ABNF_NICKNAME] ?? NULL, // 用户名字
            Contracts\ABNF_NICKNAME => $user[Contracts\ABNF_NICKNAME] ?? NULL, // 用户昵称
            Contracts\ABNF_AVATAR   => $user['headimgurl'] ?? NULL, // 用户头像
            Contracts\ABNF_UNION_ID => $user['union_id'] ?? NULL, // 联合 ID
        ]);
    }
    
    /**
     * 重定向到指定的网址，或者返回当前重定向的URL。
     *
     * @param string|null $redirectUrl 要重定向到的URL。
     *
     * @return string 当前重定向的URL。
     */
    public function redirect(?string $redirectUrl = NULL): string
    {
        return '';
    }
    
    
    /**
     * 为提供者配置映射。
     *
     * @return void
     */
    function configMapping(): void
    {
        // TODO: Implement configMapping() method.
    }
    
    protected function mapToUserObject(array $user): Contracts\UserInterface
    {
        return new User([
            Contracts\ABNF_ID       => $user['openid'] ?? NULL, // 用户唯一标识
            Contracts\ABNF_NAME     => $user[Contracts\ABNF_NICKNAME] ?? NULL, // 用户名字
            Contracts\ABNF_NICKNAME => $user[Contracts\ABNF_NICKNAME] ?? NULL, // 用户昵称
            Contracts\ABNF_AVATAR   => $user['headimgurl'] ?? NULL, // 用户头像
            Contracts\ABNF_UNION_ID => $user['union_id'] ?? NULL, // 联合 ID
        ]);
    }
    
    protected function getUserFromToken(string $token): array
    {
        // TODO: Implement getUserFromToken() method.
        return [];
    }
    
    protected function getAuthUrl(): string
    {
        // TODO: Implement getAuthUrl() method.
        return '';
    }
    
    public function getShareUrl()
    {
        // TODO: Implement getShareUrl() method.
    }
    
    public function getShareQrCode()
    {
        // TODO: Implement getShareQrCode() method.
    }
}