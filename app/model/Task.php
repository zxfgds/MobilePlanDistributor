<?php

namespace app\model;

use support\Model;

/**
 *
 */
class Task extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = NULL;
    
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = FALSE;
    
    const TYPE_IMPORT_NUMBER = 0;
    
    const TYPES = [
        self::TYPE_IMPORT_NUMBER => '导入号码',
    ];
    
    // 状态码定义
    const STATUS_TODO      = 0;
    const STATUS_DOING     = 1;
    const STATUS_DONE      = 2;
    const STATUS_CANCELLED = 3;
    const STATUS_FAILED    = 4;
    const STATUS_ON_HOLD   = 5;
    
    // 状态数组
    public static array $statusNames = [
        self::STATUS_TODO      => '待处理',
        self::STATUS_DOING     => '进行中',
        self::STATUS_DONE      => '已完成',
        self::STATUS_CANCELLED => '已取消',
        self::STATUS_FAILED    => '已失败',
        self::STATUS_ON_HOLD   => '暂停中',
    ];
}
