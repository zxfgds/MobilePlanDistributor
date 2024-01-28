<?php

namespace app\model;

use support\Model;

/**
 * @property integer $id (主键)
 * @property integer $shop_id 店铺ID
 * @property string $amount 提现金额
 * @property integer $status 状态: 0:等待审核 ,1 成功 2:拒绝,3:取消
 * @property string $proof 转账证明
 * @property string $message 审核消息
 * @property mixed $receive_info 收款账号信息
 */
class ShopWithdrawLog extends Model
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
