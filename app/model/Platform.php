<?php

namespace app\model;

use support\Model;

class Platform extends Model
{
    const PLATFORM_MINIAPP          = 10; // 小程序
    const PLATFORM_WECHAT_MINIAPP   = 11; // 微信小程序
    const PLATFORM_ALIPAY_MINIAPP   = 12; // 支付宝小程序
    const PLATFORM_BAIDU_MINIAPP    = 13; // 百度小程序
    const PLATFORM_DOUYIN_MINIAPP   = 14; // 抖音小程序
    const PLATFORM_TOUTIAO_MINIAPP  = 15; // 头条小程序
    const PLATFORM_QQ_MINIAPP       = 16; // QQ 小程序
    const PLATFORM_KUAISHOU_MINIAPP = 17; // 快手小程序
    const PLATFORM_H5               = 20; // H5
    const PLATFORM_WECHAT_H5        = 21; // 微信内H5
    const PLATFORM_ALIPAY_H5        = 22; // 支付宝内H5
    const PLATFORM_BAIDU_H5         = 23; // 百度内H5
    const PLATFORM_DOUYIN_H5        = 24; // 抖音内H5
    const PLATFORM_TOUTIAO_H5       = 25; // 头条内H5
    const PLATFORM_QQ_H5            = 26; // QQ 内H5
    const PLATFORM_KUAISHOU_H5      = 27; // 快手内H5
    const PLATFORM_QUICKAPP         = 30; // 快应用
    const PLATFORM_HUAWEI_QUICKAPP  = 31; // 快应用-华为
    const PLATFORM_OPPO_QUICKAPP    = 32; // 快应用-OPPO
    const PLATFORM_XIAOMI_QUICKAPP  = 33; // 快应用-小米
    const PLATFORM_LENOVO_QUICKAPP  = 34; // 快应用-联想
    const PLATFORM_VIVO_QUICKAPP    = 35; // 快应用-vivo
    const PLATFORM_NUBIA_QUICKAPP   = 36; // 快应用-nubia
    const PLATFORM_MEIZU_QUICKAPP   = 37; // 快应用-魅族
    const PLATFORM_HONOR_QUICKAPP   = 38; // 快应用-荣耀
    
    const PLATFORM_NAMES = [
        self::PLATFORM_MINIAPP          => '小程序',
        self::PLATFORM_WECHAT_MINIAPP   => '微信小程序',
        self::PLATFORM_ALIPAY_MINIAPP   => '支付宝小程序',
        self::PLATFORM_BAIDU_MINIAPP    => '百度小程序',
        self::PLATFORM_DOUYIN_MINIAPP   => '抖音小程序',
        self::PLATFORM_TOUTIAO_MINIAPP  => '头条小程序',
        self::PLATFORM_QQ_MINIAPP       => 'QQ小程序',
        self::PLATFORM_KUAISHOU_MINIAPP => '快手小程序',
        self::PLATFORM_H5               => 'H5',
        self::PLATFORM_WECHAT_H5        => '微信H5',
        self::PLATFORM_ALIPAY_H5        => '支付宝H5',
        self::PLATFORM_BAIDU_H5         => '百度H5',
        self::PLATFORM_DOUYIN_H5        => '抖音H5',
        self::PLATFORM_TOUTIAO_H5       => '头条H5',
        self::PLATFORM_QQ_H5            => 'QQH5',
        self::PLATFORM_KUAISHOU_H5      => '快手H5',
        self::PLATFORM_QUICKAPP         => '快应用',
        self::PLATFORM_HUAWEI_QUICKAPP  => '快应用-华为',
        self::PLATFORM_OPPO_QUICKAPP    => '快应用-OPPO',
        self::PLATFORM_XIAOMI_QUICKAPP  => '快应用-小米',
        self::PLATFORM_LENOVO_QUICKAPP  => '快应用-联想',
        self::PLATFORM_VIVO_QUICKAPP    => '快应用-vivo',
        self::PLATFORM_NUBIA_QUICKAPP   => '快应用-nubia',
        self::PLATFORM_MEIZU_QUICKAPP   => '快应用-魅族',
        self::PLATFORM_HONOR_QUICKAPP   => '快应用-荣耀',
    ];
    
    // 社会化登陆映射
    const PLATFORM_DRIVERS = [
        self::PLATFORM_MINIAPP          => 'mini',
        self::PLATFORM_WECHAT_MINIAPP   => 'wechat',
        self::PLATFORM_ALIPAY_MINIAPP   => 'alipay',
        self::PLATFORM_BAIDU_MINIAPP    => 'baidu',
        self::PLATFORM_DOUYIN_MINIAPP   => 'douyin',
        self::PLATFORM_TOUTIAO_MINIAPP  => 'toutiao',
        self::PLATFORM_QQ_MINIAPP       => 'qq',
        self::PLATFORM_KUAISHOU_MINIAPP => 'kuaishou',
        self::PLATFORM_H5               => 'h5',
        self::PLATFORM_WECHAT_H5        => 'wechat',
        self::PLATFORM_ALIPAY_H5        => 'alipay',
        self::PLATFORM_BAIDU_H5         => 'baidu',
        self::PLATFORM_DOUYIN_H5        => 'douyin',
        self::PLATFORM_TOUTIAO_H5       => 'toutiao',
        self::PLATFORM_QQ_H5            => 'qq',
        self::PLATFORM_KUAISHOU_H5      => 'kuaishou',
        self::PLATFORM_QUICKAPP         => 'quick',
        self::PLATFORM_HUAWEI_QUICKAPP  => 'quick_huawei',
        self::PLATFORM_OPPO_QUICKAPP    => 'quick_oppo',
        self::PLATFORM_XIAOMI_QUICKAPP  => 'quick_xiaomi',
        self::PLATFORM_LENOVO_QUICKAPP  => 'quick_lenovo',
        self::PLATFORM_VIVO_QUICKAPP    => 'quick_vivo',
        self::PLATFORM_NUBIA_QUICKAPP   => 'quick_nubia',
        self::PLATFORM_MEIZU_QUICKAPP   => 'quick_meizu',
        self::PLATFORM_HONOR_QUICKAPP   => 'quick_honor',
    ];
    
    /**
     * Determine the platform type based on the given platform ID.
     *
     * @param int $platformId The ID of the platform.
     *
     * @return string The platform type.
     */
    public static function platformType(int $platformId): string
    {
        return match ($platformId) {
            static::PLATFORM_MINIAPP, static::PLATFORM_WECHAT_MINIAPP, static::PLATFORM_ALIPAY_MINIAPP, static::PLATFORM_BAIDU_MINIAPP, static::PLATFORM_DOUYIN_MINIAPP, static::PLATFORM_TOUTIAO_MINIAPP, static::PLATFORM_QQ_MINIAPP, static::PLATFORM_KUAISHOU_MINIAPP => "mini",
            static::PLATFORM_H5 => "h5",
            static::PLATFORM_WECHAT_H5, static::PLATFORM_ALIPAY_H5, static::PLATFORM_BAIDU_H5, static::PLATFORM_DOUYIN_H5, static::PLATFORM_TOUTIAO_H5, static::PLATFORM_QQ_H5, static::PLATFORM_KUAISHOU_H5 => "official",
            static::PLATFORM_HUAWEI_QUICKAPP => 'quick_huawei',
            static::PLATFORM_QUICKAPP, static::PLATFORM_OPPO_QUICKAPP, static::PLATFORM_XIAOMI_QUICKAPP, static::PLATFORM_LENOVO_QUICKAPP, static::PLATFORM_VIVO_QUICKAPP, static::PLATFORM_NUBIA_QUICKAPP, static::PLATFORM_MEIZU_QUICKAPP, static::PLATFORM_HONOR_QUICKAPP => 'quick',
            default => 'h5',
        };
    }
    
    
}