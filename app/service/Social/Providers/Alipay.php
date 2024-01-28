<?php

namespace app\service\Social\Providers;

use app\service\Social\Contracts\ProviderInterface;
use app\service\Social\Contracts\UserInterface;

class Alipay extends Base
{
    public const name = 'alipay';
    
    /**
     * @inheritDoc
     */
    public function redirect(?string $redirectUrl = NULL): string
    {
        // TODO: Implement redirect() method.
    }
    
    /**
     * @inheritDoc
     */
    public function userFromCode(string $code): UserInterface
    {
        // TODO: Implement userFromCode() method.
    }
    
    /**
     * @inheritDoc
     */
    public function userFromToken(string $token): UserInterface
    {
        // TODO: Implement userFromToken() method.
    }
    
    /**
     * @inheritDoc
     */
    public function withState(string $state): ProviderInterface
    {
        // TODO: Implement withState() method.
    }
    
    /**
     * @inheritDoc
     */
    public function withRedirectUrl(string $redirectUrl): ProviderInterface
    {
        // TODO: Implement withRedirectUrl() method.
    }
    
    /**
     * @inheritDoc
     */
    public function scopes(array $scopes): \app\service\Social\Contracts\ProviderInterface
    {
        // TODO: Implement scopes() method.
    }
    
    /**
     * @inheritDoc
     */
    public function with(array $parameters): \app\service\Social\Contracts\ProviderInterface
    {
        // TODO: Implement with() method.
    }
    
    /**
     * @inheritDoc
     */
    function configMapping(): void
    {
        // TODO: Implement configMapping() method.
    }
}