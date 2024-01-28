<?php

namespace app\model;

use support\Model;

/**
 * @property integer $id (主键)
 * @property string $order_no 
 * @property string $out_trade_no 外部订单号
 * @property string $operator_trade_no 运营商订单号
 * @property integer $product_type 订单类型
 * @property integer $shop_type 来源: 0:本系统商城,抖店,快店,红店
 * @property integer $shop_id 店铺ID
 * @property integer $channel_id 渠道ID
 * @property integer $customer_id 客户ID
 * @property integer $platform_id 平台
 * @property integer $from 来源
 * @property integer $from_id 来源ID
 * @property integer $status 主状态 :新订单,已支付,已完成,失败
 * @property integer $intercept_status 拦截状态
 * @property mixed $intercept_info 
 * @property string $amount 价格
 * @property integer $product_id 产品ID
 * @property string $phone_num 手机号码
 * @property integer $express_status 物流状态
 * @property mixed $express_data 物流信息
 * @property integer $settle_status 分润状态
 * @property integer $operator_sync_status 同步状态
 */
class Order extends Model
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
