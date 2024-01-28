<?php

namespace app\model;

use support\Model;

/**
 * @property integer $id (主键)
 * @property mixed $config 配置
 * @property string $username 用户名
 * @property string $password 密码
 * @property string $phone_num 
 * @property string $wechat_open_id 
 * @property integer $wechat_followed 
 * @property integer $parent_id 
 * @property string $balance 余额
 * @property string $balance_locked 锁定余额
 * @property mixed $alipay_info 
 * @property mixed $bank_info
 */
class Shop extends Model
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
