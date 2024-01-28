<?php

namespace app\logic;

use app\model\ClientConfig;
use support\Log;

class ClientConfigLogic extends BaseLogic
{
	protected static string $model = ClientConfig::class;


	/**
	 * 获取配置
	 * @return array|null
	 */
	public static function getConfig ()
	: ?array
	{
		// 从客户端解析参数，并将其存在 $requestParams 变量中
		$requestParams = parseClientParams();

		// 获取请求参数中的shop_id，如果不存在则默认为0
		$shopId = $requestParams['shop_id'] ?? 0;

		// 获取请求参数中的channel_id，如果不存在则默认为0
		$channelId = $requestParams['channel_id'] ?? 0;

		// 如果有channel_id，则获取与channel_id有关的配置
		if (!empty($channelId)) return static::getChannelConfig($channelId, $shopId);

		// 如果有shop_id，则获取与shop_id有关的配置
		if (!empty($shopId)) return static::getShopConfig($shopId);

		// 否则返回系统配置
		return static::getSystemConfig();
	}

	public static function getChannelConfig ($channelId, $shopId = 0)
	{
		$fetch = ClientConfig::where('channel_id', $channelId)->first();

		// 如果没有为 channel 单独配置 ,则返回店铺配置 或者 系统配置
		if (empty($fetch)) {
			if (!empty($shopId)) {
				return static::getShopConfig($shopId);
			}
			return static::getSystemConfig();
		}

	}

	/**
	 * @param $shopId
	 * @return array
	 */
	public static function getShopConfig ($shopId)
	: array
	{
		$fetch = ClientConfig::where('shop_id', $shopId)
		                     ->where('channel_id', 0)
		                     ->first();

		if (!$fetch) return static::getSystemConfig();

		$config = $fetch->config;

		return $config ? json_decode($config, TRUE) : static::getSystemConfig();
	}

	/**
	 * @return array
	 */
	public static function getSystemConfig ()
	: array
	{
		try {
			// 尝试从SettingLogic中获取相关配置，如果失败则使用默认配置
			return SettingLogic::get('shop') ?? static::default();
		} catch (\Exception $e) {
			Log::error($e);
			// 发生异常时返回默认配置
			return static::default();
		}
	}

	public static function default ()
	: array
	{
		return [
			"name"              => env("APP_NAME"),// 商城名称
			"ipToAddr"          => 1,// ip到地址
			"autoSubmit"        => 1,// 自动提交
			"fancyNumMasking"   => 1,// 隐藏中间四位
			"packageNumMasking" => 1,// 脱敏
			"recommendOnFail"   => 1,// 自动推荐
			"recommendOnReturn" => 1,// 自动推荐
		];
	}
}