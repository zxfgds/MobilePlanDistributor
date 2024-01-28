<?php

namespace app\service\Social\platforms;

use app\service\Social\Contracts\ProviderInterface;
use app\service\Social\Contracts\UserInterface;

class Weixin extends Base
{
    /**
     * @return mixed
     */
    public function getUserInfoByCode()
    {
        // TODO: Implement getUserInfoByCode() method.
    }
    
    /**
     * @return void
     */
    protected function configMapping(): void
    {
        // TODO: Implement configMapping() method.
    }
    
    /**
     * @param string|null $redirectUrl
     *
     * @return string
     */
    public function redirect(?string $redirectUrl = NULL): string
    {
        // TODO: Implement redirect() method.
    }
    
    /**
     * @param string $code
     *
     * @return UserInterface
     */
    public function userFromCode(string $code): UserInterface
    {
        // TODO: Implement userFromCode() method.
    }
    
    /**
     * @param string $token
     *
     * @return UserInterface
     */
    public function userFromToken(string $token): UserInterface
    {
        // TODO: Implement userFromToken() method.
    }
    
    /**
     * @param string $state
     *
     * @return ProviderInterface
     */
    public function withState(string $state): ProviderInterface
    {
        // TODO: Implement withState() method.
    }
    
    /**
     * @param string $redirectUrl
     *
     * @return ProviderInterface
     */
    public function withRedirectUrl(string $redirectUrl): ProviderInterface
    {
        // TODO: Implement withRedirectUrl() method.
    }
    
    /**
     * @param array $scopes
     *
     * @return self
     */
    public function scopes(array $scopes): \app\service\Social\Contracts\ProviderInterface
    {
        // TODO: Implement scopes() method.
    }
    
    /**
     * @param array $parameters
     *
     * @return self
     */
    public function with(array $parameters): \app\service\Social\Contracts\ProviderInterface
    {
        // TODO: Implement with() method.
    }
    
    /**
     * @return string|null
     */
    public function getClientId(): ?string
    {
        // TODO: Implement getClientId() method.
    }
    
    /**
     * @return string|null
     */
    public function getClientSecret(): ?string
    {
        // TODO: Implement getClientSecret() method.
    }
}