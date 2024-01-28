<?php

namespace app\library;

/**
 * Class ApiCode
 *
 * 定义 API 错误码
 */
class HttpCode
{
    // 系统错误码
    const SYSTEM_ERROR        = 1000; // 系统错误
    const AUTH_ERROR          = 1001; // 鉴权失败
    const PARAM_ERROR         = 1002; // 参数错误
    const DATABASE_ERROR      = 1003; // 数据库错误
    const SERVICE_UNAVAILABLE = 1004; // 服务不可用
    
    // 业务错误码
    const MISSING_REQUIRED_FIELD = 2001; // 缺少必填字段
    const INVALID_FIELD_FORMAT   = 2002; // 字段格式不正确
    const DATA_NOT_EXIST         = 2003; // 数据不存在
    const DATA_ALREADY_EXIST     = 2004; // 数据已存在
    const DATA_CREATE_FAILED     = 2005; // 数据创建失败
    const DATA_UPDATE_FAILED     = 2006; // 数据更新失败
    const DATA_DELETE_FAILED     = 2007; // 数据删除失败
    const REQUEST_LIMITED        = 2008; // 请求次数超限
    const PERMISSION_DENIED      = 2009; // 权限不足
    
    // 通用错误码
    const SUCCESS = 0; // 成功
    const ERROR   = 1; // 失败
    
    // 错误码与中文释义对应数组
    public static array $msg = [
        self::SYSTEM_ERROR           => '系统错误',
        self::AUTH_ERROR             => '鉴权失败',
        self::PARAM_ERROR            => '参数错误',
        self::DATABASE_ERROR         => '数据库错误',
        self::SERVICE_UNAVAILABLE    => '服务不可用',
        self::MISSING_REQUIRED_FIELD => '缺少必填字段',
        self::INVALID_FIELD_FORMAT   => '字段格式不正确',
        self::DATA_NOT_EXIST         => '数据不存在',
        self::DATA_ALREADY_EXIST     => '数据已存在',
        self::DATA_CREATE_FAILED     => '数据创建失败',
        self::DATA_UPDATE_FAILED     => '数据更新失败',
        self::DATA_DELETE_FAILED     => '数据删除失败',
        self::REQUEST_LIMITED        => '请求次数超限',
        self::PERMISSION_DENIED      => '权限不足',
        self::SUCCESS                => '成功',
        self::ERROR                  => '失败',
    ];
}
