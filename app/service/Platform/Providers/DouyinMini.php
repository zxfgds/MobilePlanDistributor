<?php

namespace app\service\Platform\Providers;

use app\service\Platform\Contracts;
use app\service\Platform\User;

class DouyinMini extends Base implements Contracts\MiniInterface
{
    public const name             = 'douyin_mini';
    public const BASE_URL         = "https://developer.toutiao.com/api/apps/";
    public const SANDBOX_BASE_URL = "https://open-sandbox.douyin.com/api/apps/";
    
    public function __construct(array $config)
    {
        parent::__construct($config);
        
    }
    
    protected function mapToUserObject(array $user): Contracts\UserInterface
    {
        return new User([
            'id'       => $user['openid'],
            'name'     => $user['nickname'],
            'avatar'   => $user['avatar'],
            'email'    => '',
            'original' => $user,
            'provider' => self::name,
        ]);
    }
    
    protected function getUserFromToken(string $token): array
    {
        return [];
    }
    
    
    /**
     * 根据提供的授权码返回一个UserInterface对象。
     *
     * @param string $code 授权码。
     *
     */
    public function userFromCode(string $code): Contracts\UserInterface
    {
        return new User([
        
        ]);
    }
    
    /**
     * 为提供者配置映射。
     *
     * @return void
     */
    function configMapping(): void
    {
        // do nothing
    }
    
    /**
     * 获取授权链接
     *
     * @return string 授权链接
     */
    protected function getAuthUrl(): string
    {
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