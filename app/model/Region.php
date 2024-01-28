<?php

namespace app\model;

use support\Model;

/**
 * @property integer $id          (主键)
 * @property integer $level       层级
 * @property integer $parent_code 父级行政代码
 * @property integer $area_code   行政代码
 * @property integer $zip_code    邮政编码
 * @property string  $city_code   区号
 * @property string  $name        名称
 * @property string  $short_name  简称
 * @property string  $merger_name 组合名
 * @property string  $pinyin      拼音
 * @property string  $lng         经度
 * @property string  $lat         纬度
 */
class Region extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'regions';
    
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
