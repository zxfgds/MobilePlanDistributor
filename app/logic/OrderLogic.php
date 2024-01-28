<?php

namespace app\logic;

use app\model\Order;

class OrderLogic extends BaseLogic
{
	protected static string $model = Order::class;
	
	
	/*
	 * 补全订单信息
	 */
	protected static function format($data): array
	{
		$orderData = [
			'product'              => '', //Model/Product::PRODUCTS;
			'status'               => '',
			'price'                => '',
			'number'               => '',
			'platform'             => '',
			'order_no'             => '',
			'from'                 => '',
			'from_id'              => '',
			'shop_id'              => 0,
			'channel_id'           => 0,
			'customer_id'          => 0,
			'express_company_code' => '',
			'express_no'           => '',
			'express_data'         => '',
			'created_at'           => '',
		];
		return parent::format($data);
	}
	
	public static function expressed($orderNo, $expressNo, $expressCompanyCode)
	{
		//todo: 创建物流公司表 , 处理 物流公司codeMap
	}
	
	public static function fail($orderNo, $status, $reason = NULL)
	{
	}
	
	public static function changeStatus($orderNo, $status, $msg = NULL)
	{
	}
	
	/**
	 * 在创建前执行的逻辑
	 */
	protected static function beforeCreate()
	{
	}
	
	/**
	 * 创建完成之后执行的逻辑
	 */
	protected static function created()
	{
	}
	
	/**
	 * 在支付前执行的逻辑
	 */
	protected static function beforePay()
	{
	}
	
	/**
	 * 支付完成之后执行的逻辑
	 */
	protected static function payed()
	{
	}
	
	/**
	 * 在取消前执行的逻辑
	 */
	protected static function beforeCancel()
	{
	}
	
	/**
	 * 取消完成之后执行的逻辑
	 */
	protected static function canceled()
	{
	}
	
	/**
	 * 在退款前执行的逻辑
	 */
	protected static function beforeRefund()
	{
	}
	
	/**
	 * 退款完成之后执行的逻辑
	 */
	protected static function refunded()
	{
	}
	
	/**
	 * 在删除前执行的逻辑
	 *
	 * @param $data*/
	protected static function beforeDelete($data): void
	{
	}
	
	/**
	 * 删除完成之后执行的逻辑
	 */
	protected static function deleted()
	{
	}
	
	/**
	 * 在更新前执行的逻辑
	 */
	protected static function beforeUpdate()
	{
	}
	
	/**
	 * 更新完成之后执行的逻辑
	 */
	protected static function updated()
	{
	}
	
	/**
	 * 在结算前执行的逻辑
	 */
	protected static function beforeSettle()
	{
	}
	
	/**
	 * 结算完成之后执行的逻辑
	 */
	protected static function settled()
	{
	}
	
	
}