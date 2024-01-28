<?php

namespace app\model;

use support\Model;

class Product extends Model
{
    const PACKAGE        = 1;
    const VIRTUAL_NUMBER = 2;
    const LUCKY_NUMBER   = 3;
    const IOT_CARD       = 4;
    const BROADBAND      = 5;
    
    const PRODUCTS = [
        self::PACKAGE        => '资费套餐',
        self::VIRTUAL_NUMBER => '虚商靓号',
        self::LUCKY_NUMBER   => '普通靓号',
        self::IOT_CARD       => '物联网卡',
        self::BROADBAND      => '宽带',
    ];
}