<?php

namespace app\service\Social;

class Service
{
    
    protected array $config;
    
    protected const PROVIDERS = [
    
    ];
    
    public function __construct($config)
    {
        $this->config = $config;
    }
    
    
}