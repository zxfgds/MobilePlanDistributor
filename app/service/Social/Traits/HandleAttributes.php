<?php

namespace app\service\Social\Traits;

trait HandleAttributes
{
    protected array $attributes = [];
    
    public function getAttributes(): array
    {
        return $this->attributes;
    }
    
    
    public function getAttribute(string $name, mixed $default = NULL): mixed
    {
        return $this->attributes[$name] ?? $default;
    }
    
    public function setAttribute(string $name, mixed $value): self
    {
        $this->attributes[$name] = $value;
        return $this;
    }
    
    public function hasAttribute(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }
    
    public function removeAttribute(string $name): self
    {
        unset($this->attributes[$name]);
        return $this;
    }
}