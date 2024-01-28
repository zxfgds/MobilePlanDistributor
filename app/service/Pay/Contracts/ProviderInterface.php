<?php

namespace app\service\Pay\Contracts;

interface ProviderInterface
{
    public function createOrder();
}