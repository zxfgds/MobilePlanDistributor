<?php

namespace app\library;

class Router
{
    public static function init(): void
    {
        $routeFiles = glob(base_path('route/*.php'));
        
        foreach ($routeFiles as $file) {
            require_once $file;
        }
    }
}