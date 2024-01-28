<?php

namespace app\model;

use support\Model;

/**
 * @property integer $id       (主键)
 * @property string  $category 分类
 * @property string  $key
 * @property string  $value
 */
class Setting extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settings';
    
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
    
    protected $fillable = ['category', 'key', 'value'];
}
