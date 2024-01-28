<?php

namespace App\Library;

use RedisException;

/**
 * 短信管理器
 *
 * @package App\Library
 */
class SmsManager
{
	/**
	 * 发送短信验证码
	 *
	 * @param string $phoneNumber     接收短信验证码的手机号
	 * @param int    $codeLength      验证码长度
	 * @param int    $expireInSeconds 验证码过期时间（秒）
	 *
	 * @return bool 发送成功返回 true，失败返回 false
	 * @throws RedisException
	 */
	public static function sendVerificationCode(string $phoneNumber, int $codeLength = 6, int $expireInSeconds = 300): bool
	{
		// 生成随机验证码
		$verificationCode = rand(pow(10, $codeLength - 1), pow(10, $codeLength) - 1);
		
		// 发送短信验证码
		if (self::sendMessage($phoneNumber, $verificationCode)) {
			// 将验证码存入缓存
			RedisCache::put("sms_verification_code:$phoneNumber", $verificationCode, $expireInSeconds);
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	/**
	 * 校验短信验证码
	 *
	 * @param string $phoneNumber 手机号
	 * @param string $inputCode   用户输入的验证码
	 *
	 * @return bool 验证码正确返回 true，错误返回 false
	 * @throws RedisException
	 */
	public static function verifyCode(string $phoneNumber, string $inputCode): bool
	{
		$storedCode = RedisCache::get("sms_verification_code:$phoneNumber");
		
		if ($storedCode && $inputCode === $storedCode) {
			// 验证成功，删除缓存中的验证码
			RedisCache::forget("sms_verification_code:$phoneNumber");
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	/**
	 * 发送短信
	 *
	 * @param string $phoneNumber 接收短信的手机号
	 * @param string $message     短信内容
	 *
	 * @return bool 发送成功返回 true，失败返回 false
	 */
	private static function sendMessage(string $phoneNumber, string $message): bool
	{
		// 在这里添加短信发送逻辑
		// 返回 true 表示发送成功，返回 false 表示发送失败
		
		// 示例代码：
		// $result = YourSmsService::send($phoneNumber, $message);
		// return $result;
		
		return FALSE; // 暂时返回 false，等待您补充具体的发送逻辑
	}
}
