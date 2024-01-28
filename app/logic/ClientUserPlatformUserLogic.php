<?php

namespace app\logic;

use app\exception\CustomException;
use app\model\ClientUserPlatformUser;

class ClientUserPlatformUserLogic extends BaseLogic
{
	protected static string $model = ClientUserPlatformUser::class;
	
	
	/**
	 * 创建数据记录
	 *
	 * @param array $data 数据内容
	 *
	 * @return bool|int 返回创建结果，成功返回true，失败返回false或错误码
	 * @throws CustomException
	 */
	public static function create($data): bool|int
	{
		$data['platform_id'] = PlatformLogic::getPlatformId(); // 获取当前平台id并添加到数据中
		return parent::create($data); // 调用父类的create方法创建数据记录
	}

	
	/**
	 * 根据openid获取平台用户详细信息
	 *
	 * @param string $openId     OpenID字符串
	 * @param bool   $onlyUserId 是否只返回用户ID
	 *
	 * @return array|int 返回平台用户信息数组或用户ID，如果用户不存在返回空数组或0
	 */
	public static function getDetailByOpenId($openId, $onlyUserId = FALSE): int|array
	{
		
		$platformId = PlatformLogic::getPlatformId();
		// 查询平台用户
		$platformUser = static::$model::where('open_id', $openId)->where('platform_id', $platformId)->first();
		
		// 如果只需要返回用户ID，则直接返回用户ID
		if ($onlyUserId) {
			return $platformUser ? $platformUser->client_user_id : 0;
		}
		
		// 否则返回平台用户信息数组
		return $platformUser ? $platformUser->toArray() : [];
	}
	
}