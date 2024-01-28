<?php

namespace app\model;

use support\Model;

/**
 * @property integer $id (主键)
 * @property string $name 品牌名称
 * @property string $website 品牌名称
 * @property string $active_url 激活链接
 * @property string $active_intro 激活说明
 * @property string $remark 备注
 */
class NumberBrand extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = null;

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
    public $timestamps = false;
    
    
}
