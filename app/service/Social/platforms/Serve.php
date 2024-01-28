<?php

namespace app\service\Social\platforms;

abstract class Serve
{
    public function __construct()
    {
    }
    
    public function __call($name, $arguments)
    {
    }
    
    abstract public function getUserInfoByCode();
    
    abstract public function getUserIdByCode();
}