<?php

namespace app\model;

use support\Model;

class Number extends Model
{
    public const STATUS_NOT_YET_ON_SHELF   = 0; // 未上架
    public const STATUS_NORMAL             = 1; // 正常(销售中)
    public const STATUS_LOCKED             = 2; // 锁定
    public const STATUS_OFF_SHELF          = 3; // 下架
    public const STATUS_SOLD_OUT           = 4; // 已售出
    public const STATUS_WAITING_FOR_REVIEW = 5; // 等待审核
    
    public static array $STATUS_NAMES = [
        self::STATUS_NOT_YET_ON_SHELF   => '未上架',
        self::STATUS_NORMAL             => '正常',
        self::STATUS_LOCKED             => '锁定',
        self::STATUS_OFF_SHELF          => '下架',
        self::STATUS_SOLD_OUT           => '已售出',
        self::STATUS_WAITING_FOR_REVIEW => '等待审核',
    ];
    
    
    // 类型码定义
    const TYPE_FANCY   = 0;
    const TYPE_PACKAGE = 1;
    const TYPE_NORMAL  = 2;
    
    // 类型数组
    public static array $typeNames = [
        self::TYPE_NORMAL  => '普通',
        self::TYPE_FANCY   => '靓号',
        self::TYPE_PACKAGE => '套餐',
    ];
    
    public static function getStatusName(int $status): string
    {
        return self::$STATUS_NAMES[$status] ?? '未知状态';
    }
}
