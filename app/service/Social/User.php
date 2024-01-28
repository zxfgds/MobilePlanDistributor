<?php

namespace app\service\Social;

use app\service\Social\Contracts;
use app\service\Social\Traits\HandleAttributes;


class User implements Contracts\UserInterface
{
    use HandleAttributes;
    
    
    public function __construct(array $attributes, protected ?Contracts\ProviderInterface $provider = NULL)
    {
        $this->attributes = $attributes;
    }
    
    public function getId(): mixed
    {
        return $this->getAttribute(Contracts\ABNF_ID);
    }
    
    public function getNickname(): ?string
    {
        return $this->getAttribute(Contracts\ABNF_NICKNAME);
    }
    
    public function getName(): ?string
    {
        return $this->getAttribute(Contracts\ABNF_NAME);
    }
    
    public function getAvatar(): ?string
    {
        return $this->getAttribute(Contracts\ABNF_AVATAR);
    }
    
    public function getProvider(): Contracts\ProviderInterface
    {
        // TODO: Implement getProvider() method.
    }
    
    public function setRefreshToken(?string $refreshToken): Contracts\UserInterface
    {
        // TODO: Implement setRefreshToken() method.
        return $this;
    }
    
    public function setExpiresIn(int $expiresIn): Contracts\UserInterface
    {
        // TODO: Implement setExpiresIn() method.
        return $this;
    }
    
    public function setTokenResponse(array $response): Contracts\UserInterface
    {
        // TODO: Implement setTokenResponse() method.
        return $this;
    }
    
    public function getTokenResponse(): mixed
    {
        // TODO: Implement getTokenResponse() method.
        return NULL;
    }
    
    public function setProvider(Contracts\ProviderInterface $provider): Contracts\UserInterface
    {
        // TODO: Implement setProvider() method.
        return $this;
    }
    
    public function getRaw(): array
    {
        // TODO: Implement getRaw() method.
        return [];
    }
    
    public function setRaw(array $user): Contracts\UserInterface
    {
        // TODO: Implement setRaw() method.
        return $this;
    }
}