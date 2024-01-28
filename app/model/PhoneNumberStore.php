<?php

namespace app\model;

use support\Model;

/**
 * @property integer $id      (主键)
 * @property string  $name    名称
 * @property integer $shop_id 店铺
 * @property integer $type    类型: 0 :虚商号库, 1:套餐号库
 * @property integer $status  状态; 0 等待审核, 1:审核中 2:审核失败  3:file
 * @property integer $weight  权重
 */
class PhoneNumberStore extends Model
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
}
