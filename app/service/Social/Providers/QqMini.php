<?php

namespace app\service\Social\Providers;

use app\service\Social\Contracts\ProviderInterface;
use app\service\Social\Contracts\UserInterface;

class QqMini extends Base
{
    public const name = 'qq_mini';
    
    /**
     * 重定向到指定的网址，或者返回当前重定向的URL。
     *
     * @param string|null $redirectUrl 要重定向到的URL。
     *
     * @return string 当前重定向的URL。
     */
    public function redirect(?string $redirectUrl = NULL): string
    {
        // TODO: Implement redirect() method.
    }
    
    /**
     * 根据提供的授权码返回一个UserInterface对象。
     *
     * @param string $code 授权码。
     *
     * @return UserInterface UserInterface对象。
     */
    public function userFromCode(string $code): UserInterface
    {
        // TODO: Implement userFromCode() method.
    }
    
    /**
     * 根据提供的访问令牌返回一个UserInterface对象。
     *
     * @param string $token 访问令牌。
     *
     * @return UserInterface UserInterface对象。
     */
    public function userFromToken(string $token): UserInterface
    {
        // TODO: Implement userFromToken() method.
    }
    
    /**
     * 使用指定的状态返回ProviderInterface的新实例。
     *
     * @param string $state 新状态值。
     *
     * @return ProviderInterface 具有指定状态的ProviderInterface的新实例。
     */
    public function withState(string $state): ProviderInterface
    {
        // TODO: Implement withState() method.
    }
    
    /**
     * 使用指定的重定向URL返回ProviderInterface的新实例。
     *
     * @param string $redirectUrl 要设置的重定向URL。
     *
     * @return ProviderInterface 具有指定重定向URL的ProviderInterface的新实例。
     */
    public function withRedirectUrl(string $redirectUrl): ProviderInterface
    {
        // TODO: Implement withRedirectUrl() method.
    }
    
    /**
     * 设置提供者的作用域。
     *
     * @param array $scopes 作用域数组。
     *
     * @return self 此ProviderInterface实例。
     */
    public function scopes(array $scopes): \app\service\Social\Contracts\ProviderInterface
    {
        // TODO: Implement scopes() method.
    }
    
    /**
     * 设置提供者的参数。
     *
     * @param array $parameters 参数数组。
     *
     * @return self 此ProviderInterface实例。
     */
    public function with(array $parameters): \app\service\Social\Contracts\ProviderInterface
    {
        // TODO: Implement with() method.
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
}