<?php

namespace app\library;

use Exception;
use Firebase\JWT\JWT;

class UserToken
{
	// JWT 密钥，用于加密和解密 token
	private static string $secretKey = "123456223456aweklfldslkjkl";
	
	/**
	 * 生成 token。
	 *
	 * @param int    $userId   用户 ID
	 * @param string $userType 用户类型（admin / shop / client）
	 *
	 * @return string 生成的 token
	 * @throws Exception 当用户类型无效时抛出异常
	 */
	public static function generateToken(int $userId, string $userType): string
	{
		// 验证用户类型
		if (!in_array($userType, ['admin', 'shop', 'client'])) {
			throw new Exception('Invalid user type');
		}
		
		// 设置 JWT 载荷（payload）
		$payload = [
			'userId'   => $userId,
			'userType' => $userType,
			'iat'      => time(), // token 创建时间
			'exp'      => time() + 3600 * 24 * 7, // token 过期时间（一周）
		];
		
		// 生成并返回 token
		return JWT::encode($payload, self::$secretKey, 'HS256');
	}
	
	/**
	 * 校验 token。
	 *
	 * @param string|null $token    要校验的 token，如果不传，则从请求头部获取
	 * @param string|null $userType 要校验的用户类型，如果不传，则不校验用户类型
	 *
	 * @return int|false 如果 token 有效，返回用户 ID；否则返回 false
	 */
	public static function validateToken(?string $userType = NULL, ?string $token = NULL,): int|false
	{
		// 如果 token 未传入，从请求头部获取
		if ($token === NULL) {
			$token = request()->header('token');
		}
		
		$token = str_replace('bearer ', '', strtolower($token));
		
		try {
			// 解码 token
			$decoded = JWT::decode($token, self::$secretKey, ['HS256']);
			$payload = (array)$decoded;
			
			// 验证用户类型
			if ($userType !== NULL && $payload['userType'] !== $userType) {
				return FALSE;
			}
			
			// 返回用户 ID
			return $payload['userId'];
		} catch (Exception $e) {
			// token 无效
			return FALSE;
		}
	}
}