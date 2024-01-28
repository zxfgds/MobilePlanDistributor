<?php

namespace app\model;

use support\Model;

/**
 * @property integer $id         (主键)
 * @property string  $name       名称
 * @property string  $path       路径
 * @property string  $component  模板
 * @property string  $redirect   跳转
 * @property integer $pid        上级
 * @property integer $type       类型
 * @property integer $sort       排序
 * @property mixed   $meta       属性
 * @property mixed   $created_at 创建时间
 * @property mixed   $updated_at 更新时间
 * @property integer $order      排序
 */
class AdminMenu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_menus';
    
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
    
    
}
