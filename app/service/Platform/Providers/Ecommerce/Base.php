<?php

namespace app\service\Platform\Providers\Ecommerce;

use app\service\Platform\Providers\Serve;

abstract class Base extends Serve
{

    public const name = 'ecommerce'; // 定义名字为 "电子商务"

    public array $config; // 定义一个配置数组

    public function __construct($config)
    {
        $this->config = $config; // 构造函数，设置传入的配置
    }

// 解密函数
    abstract public function decrypt($data): array|string;

// 批量解密函数
    abstract protected function batchDecrypt($data): array;

// 签名函数
    abstract protected function sign($data): array;

// 获取订单列表函数
    abstract protected function getOrders(...$args): array;

// 获取订单详情函数
    abstract protected function getOrder($orderNo): array;

    // webhook

    // 获取商品列表

    // 上架

    // 下架

    // 批量上架

    // 批量下架
}