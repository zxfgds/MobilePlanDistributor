<?php

namespace app\model;

use support\Model;

/**
 * @property integer $id (主键)
 * @property integer $shop_id 
 * @property string $amount 变动金额 正数增加 负数减少
 * @property integer $type 类型: 订单成交 , 提现,等
 * @property mixed $info 相关订单/提现信息
 * @property string $balance_before 变动前余额
 * @property string $balance_after 变动后余额
 */
class ShopBalanceLog extends Model
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
