<?php

namespace app\service\Platform\Providers\Official;

use app\service\Platform\Providers\Serve;

abstract class Base extends Serve
{

    public const name = 'official';

    protected array $scopes = [];
    /**
     * @var mixed|null
     */
    protected ?string $redirectUrl = null;

    public function scopes(array $scopes): static
    {
        $this->scopes = $scopes;
        return $this;
    }

    public function redirect($redirectUrl = null): static
    {
        $this->redirectUrl = $redirectUrl;
        return $this;
    }

    abstract function getAuthUrl(): string;

    abstract function codeToUser(string $code);
}