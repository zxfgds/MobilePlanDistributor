<?php

namespace app\model;

use support\Model;

/**
 * @property integer $id (主键)
 * @property integer $shop_id 店铺ID
 * @property mixed $config 配置
 * @property string $name 渠道名称
 */
class Channel extends Model
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
