<?php

namespace app\service\Platform\Providers;

use app\service\Platform\Contracts;
use GuzzleHttp\Client as GuzzleClient;


abstract class Base implements Contracts\ProviderInterface
{
    // 声明常量 NAME 并赋值为 NULL
    public const NAME = NULL;
    // 声明一个 config 属性作为数组
    public array $config;
    // 声明一个 redirectUrl 属性作为可空字符串
    protected ?string $redirectUrl;
    // 声明一个 parameters 属性作为数组
    protected array $parameters = [];
    // 声明一个 scopes 属性作为数组
    protected array $scopes = [];
    // 声明一个 scopeSeparator 属性作为字符串，默认为 ','
    protected string $scopeSeparator = ',';
    // 声明一个 state 属性作为私有字符串
    protected string $state = '';
    // 声明一个 openid 属性作为私有字符串
    protected string $openid = '';
    // 声明一个 encodingType 属性作为整型，使用 PHP_QUERY_RFC1738 编码
    protected int $encodingType = PHP_QUERY_RFC1738;
    // 声明一个 expiresInKey 属性作为字符串，使用 RFC6749 协议中的过期时间键名
    protected string $expiresInKey = Contracts\RFC6749_ABNF_EXPIRES_IN;
    // 声明一个 accessTokenKey 属性作为字符串，使用 RFC6749 协议中的访问令牌键名
    protected string $accessTokenKey = Contracts\RFC6749_ABNF_ACCESS_TOKEN;
    // 声明一个 refreshTokenKey 属性作为字符串，使用 RFC6749 协议中的刷新令牌键名
    protected string $refreshTokenKey = Contracts\RFC6749_ABNF_REFRESH_TOKEN;
    protected array  $guzzleOptions   = [];
    
    protected GuzzleClient $httpClient;
    
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->configMapping();
    }
    
    /**
     * 将用户信息映射到用户对象
     *
     * @param array $user 用户信息数组
     *
     * @return Contracts\UserInterface 用户对象
     */
    abstract protected function mapToUserObject(array $user): Contracts\UserInterface;
    
    /**
     * 根据 token 获取用户信息数组
     *
     * @param string $token 用户 token
     *
     * @return array 用户信息数组
     */
    abstract protected function getUserFromToken(string $token): array;
    
    /**
     * 获取授权链接
     *
     * @return string 授权链接
     */
    abstract protected function getAuthUrl(): string;
    
    
    /**
     * 跳转至授权页面
     *
     * @param string|null $redirectUrl 重定向链接
     *
     * @return string 授权链接
     */
    public function redirect(?string $redirectUrl = NULL): string
    {
        if (!empty($redirectUrl)) {
            $this->withRedirectUrl($redirectUrl);
        }
        
        return $this->getAuthUrl();
    }
    
    /**
     * 添加 state 参数
     *
     * @param string $state state 参数
     *
     * @return Contracts\ProviderInterface 返回当前的 Provider 实例
     */
    public function withState(string $state): Contracts\ProviderInterface
    {
        $this->state = $state;
        
        return $this;
    }
    
    /**
     * 添加 scopes 参数
     *
     * @param array $scopes scopes 参数数组
     *
     * @return Contracts\ProviderInterface 返回当前的 Provider 实例
     */
    public function scopes(array $scopes): Contracts\ProviderInterface
    {
        $this->scopes = $scopes;
        
        return $this;
    }
    
    /**
     * 添加其他参数
     *
     * @param array $parameters 其他参数数组
     *
     * @return Contracts\ProviderInterface 返回当前的 Provider 实例
     */
    public function with(array $parameters): Contracts\ProviderInterface
    {
        $this->parameters = $parameters;
        
        return $this;
    }
    
    /**
     * 设置 openid
     *
     * @param string $openid 用户 openid
     *
     * @return self
     */
    public function withOpenid(string $openid): self
    {
        $this->openid = $openid;
        
        return $this;
    }
    
    /**
     * 设置授权范围分隔符
     *
     * @param string $scopeSeparator 分隔符
     *
     * @return Contracts\ProviderInterface
     */
    public function withScopeSeparator(string $scopeSeparator): Contracts\ProviderInterface
    {
        $this->scopeSeparator = $scopeSeparator;
        
        return $this;
    }
    
    /**
     * 设置重定向 URL
     *
     * @param string $redirectUrl 重定向 URL
     *
     * @return Contracts\ProviderInterface
     */
    public function withRedirectUrl(string $redirectUrl): Contracts\ProviderInterface
    {
        $this->redirectUrl = $redirectUrl;
        
        return $this;
    }
    
    /**
     * 获取配置数组
     *
     * @return array 配置数组
     */
    public function getConfig(): array
    {
        return $this->config;
    }
    
    /**
     * 获取客户端ID
     *
     * @return string|null 客户端ID
     */
    public function getClientId(): ?string
    {
        return $this->config[Contracts\RFC6749_ABNF_CLIENT_ID];
    }
    
    /**
     * 获取客户端密钥
     *
     * @return string|null 客户端密钥
     */
    public function getClientSecret(): ?string
    {
        return $this->config[Contracts\RFC6749_ABNF_CLIENT_SECRET];
    }
    
    public function getHttpClient(): GuzzleClient
    {
        return $this->httpClient ?? new GuzzleClient($this->guzzleOptions);
    }
    
    public function setGuzzleOptions(array $config): Contracts\ProviderInterface
    {
        $this->guzzleOptions = $config;
        
        return $this;
    }
    
    public function getGuzzleOptions(): array
    {
        return $this->guzzleOptions;
    }
    
    /**
     * formatScopes方法：将作用域数组格式化成字符串
     *
     * @param array  $scopes         作用域数组
     * @param string $scopeSeparator 作用域分隔符
     *
     * @return string 格式化后的作用域字符串
     */
    protected function formatScopes(array $scopes, string $scopeSeparator): string
    {
        return \implode($scopeSeparator, $scopes);
    }
    
    /**
     * getTokenFields方法：获取用于换取访问令牌的参数数组
     *
     * @param string $code 授权码
     *
     * @return array 包含用于换取访问令牌的参数的关联数组
     */
    protected function getTokenFields(string $code): array
    {
        return [
            Contracts\RFC6749_ABNF_CLIENT_ID     => $this->getClientId(),
            Contracts\RFC6749_ABNF_CLIENT_SECRET => $this->getClientSecret(),
            Contracts\RFC6749_ABNF_CODE          => $code,
            Contracts\RFC6749_ABNF_REDIRECT_URI  => $this->redirectUrl,
        ];
    }
    
    /**
     * buildAuthUrlFromBase方法：从基础认证URL构建授权URL
     *
     * @param string $url 基础认证URL
     *
     * @return string 构建的授权URL
     */
    protected function buildAuthUrlFromBase(string $url): string
    {
        $query = $this->getCodeFields() + ($this->state ? [Contracts\RFC6749_ABNF_STATE => $this->state] : []);
        
        return $url . '?' . \http_build_query($query, '', '&', $this->encodingType);
    }
    
    /**
     * getCodeFields方法：获取用于授权的参数数组
     *
     * @return array 包含用于授权的参数的关联数组
     */
    protected function getCodeFields(): array
    {
        $fields = \array_merge(
            [
                Contracts\RFC6749_ABNF_CLIENT_ID     => $this->getClientId(),
                Contracts\RFC6749_ABNF_REDIRECT_URI  => $this->redirectUrl,
                Contracts\RFC6749_ABNF_SCOPE         => $this->formatScopes($this->scopes, $this->scopeSeparator),
                Contracts\RFC6749_ABNF_RESPONSE_TYPE => Contracts\RFC6749_ABNF_CODE,
            ],
            $this->parameters
        );
        
        if ($this->state) {
            $fields[Contracts\RFC6749_ABNF_STATE] = $this->state;
        }
        
        return $fields;
    }
    
}