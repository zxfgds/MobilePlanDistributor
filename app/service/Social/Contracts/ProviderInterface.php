<?php

namespace app\service\Social\Contracts;



/** @see https://datatracker.ietf.org/doc/html/rfc6749#appendix-A.1 */
const RFC6749_ABNF_CLIENT_ID = 'client_id';
/** @see https://datatracker.ietf.org/doc/html/rfc6749#appendix-A.2 */
const RFC6749_ABNF_CLIENT_SECRET = 'client_secret';
/** @see https://datatracker.ietf.org/doc/html/rfc6749#appendix-A.3 */
const RFC6749_ABNF_RESPONSE_TYPE = 'response_type';
/** @see https://datatracker.ietf.org/doc/html/rfc6749#appendix-A.4 */
const RFC6749_ABNF_SCOPE = 'scope';
/** @see https://datatracker.ietf.org/doc/html/rfc6749#appendix-A.5 */
const RFC6749_ABNF_STATE = 'state';
/** @see https://datatracker.ietf.org/doc/html/rfc6749#appendix-A.6 */
const RFC6749_ABNF_REDIRECT_URI = 'redirect_uri';
/** @see https://datatracker.ietf.org/doc/html/rfc6749#appendix-A.7 */
const RFC6749_ABNF_ERROR = 'error';
/** @see https://datatracker.ietf.org/doc/html/rfc6749#appendix-A.8 */
const RFC6749_ABNF_ERROR_DESCRIPTION = 'error_description';
/** @see https://datatracker.ietf.org/doc/html/rfc6749#appendix-A.9 */
const RFC6749_ABNF_ERROR_URI = 'error_uri';
/** @see https://datatracker.ietf.org/doc/html/rfc6749#appendix-A.10 */
const RFC6749_ABNF_GRANT_TYPE = 'grant_type';
/** @see https://datatracker.ietf.org/doc/html/rfc6749#appendix-A.11 */
const RFC6749_ABNF_CODE = 'code';
/** @see https://datatracker.ietf.org/doc/html/rfc6749#appendix-A.12 */
const RFC6749_ABNF_ACCESS_TOKEN = 'access_token';
/** @see https://datatracker.ietf.org/doc/html/rfc6749#appendix-A.13 */
const RFC6749_ABNF_TOKEN_TYPE = 'token_type';
/** @see https://datatracker.ietf.org/doc/html/rfc6749#appendix-A.14 */
const RFC6749_ABNF_EXPIRES_IN = 'expires_in';
/** @see https://datatracker.ietf.org/doc/html/rfc6749#appendix-A.15 */
const RFC6749_ABNF_USERNAME = 'username';
/** @see https://datatracker.ietf.org/doc/html/rfc6749#appendix-A.16 */
const RFC6749_ABNF_PASSWORD = 'password';
/** @see https://datatracker.ietf.org/doc/html/rfc6749#appendix-A.17 */
const RFC6749_ABNF_REFRESH_TOKEN = 'refresh_token';
/** @see https://datatracker.ietf.org/doc/html/rfc6749#section-4.1.3 */
const RFC6749_ABNF_AUTHORATION_CODE = 'authorization_code';
/** @see https://datatracker.ietf.org/doc/html/rfc6749#section-4.4.2 */
const RFC6749_ABNF_CLIENT_CREDENTIALS = 'client_credentials';

interface ProviderInterface
{
    
    
    /**
     * 重定向到指定的网址，或者返回当前重定向的URL。
     *
     * @param string|null $redirectUrl 要重定向到的URL。
     * @return string 当前重定向的URL。
     */
    public function redirect(?string $redirectUrl = null): string;
    
    /**
     * 根据提供的授权码返回一个UserInterface对象。
     *
     * @param string $code 授权码。
     * @return UserInterface UserInterface对象。
     */
    public function userFromCode(string $code): UserInterface;
    
   
    /**
     * 使用指定的状态返回ProviderInterface的新实例。
     *
     * @param string $state 新状态值。
     * @return ProviderInterface 具有指定状态的ProviderInterface的新实例。
     */
    public function withState(string $state): ProviderInterface;
    
    /**
     * 使用指定的重定向URL返回ProviderInterface的新实例。
     *
     * @param string $redirectUrl 要设置的重定向URL。
     * @return ProviderInterface 具有指定重定向URL的ProviderInterface的新实例。
     */
    public function withRedirectUrl(string $redirectUrl): ProviderInterface;
    
    /**
     * 设置提供者的作用域。
     *
     * @param array $scopes 作用域数组。
     * @return self 此ProviderInterface实例。
     */
    public function scopes(array $scopes): self;
    
    /**
     * 设置提供者的参数。
     *
     * @param array $parameters 参数数组。
     * @return self 此ProviderInterface实例。
     */
    public function with(array $parameters): self;
    
    /**
     * 为提供者配置映射。
     *
     * @return void
     */
    function configMapping(): void;
    
}