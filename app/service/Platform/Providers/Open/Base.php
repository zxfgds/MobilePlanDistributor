<?php

namespace app\service\Platform\Providers\Open;

use app\service\Platform\Providers\Serve;

abstract class Base extends Serve
{
    public const name = 'open';

    protected array $scopes = [];

    protected ?string $redirectUrl = null;

    protected ?string $state = null;

    public function scopes(array $scopes): static
    {
        $this->scopes = $scopes;
        return $this;
    }

    public function states(array $states): static
    {
        $this->state = implode(',', $states);
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