<?php

namespace app\library;
// todo  delete
class NumberRuleMatcher
{
	/**
	 * 匹配数字
	 *
	 * @param int $number 需要进行匹配的数字
	 * @return string|null 返回匹配结果，如果没有匹配成功则返回 null
	 */
	public static function match (int $number)
	: ?string
	{
		$request = "match:" . $number;
		return self::send_request($request);
	}

	public static function send_request ($request)
	: ?string
	{
		$address = "127.0.0.1";
		$port    = 8080;

		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => 5, 'usec' => 0]);
		socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, ['sec' => 5, 'usec' => 0]);

		if ($socket === FALSE) {
			echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
			return NULL;
		}

		$result = socket_connect($socket, $address, $port);

		if ($result === FALSE) {
			echo "socket_connect() failed: reason: " . socket_strerror(socket_last_error($socket)) . "\n";
			socket_close($socket);
			return NULL;
		}

		socket_write($socket, $request, strlen($request));

		$response = "";

		while ($out = socket_read($socket, 2048)) {
			$response .= $out;
		}

		socket_close($socket);
		return $response;
	}

	/**
	 * 添加规则
	 *
	 * @param array $rules 规则数组，key为规则名，value为规则正则表达式
	 * @return string|null 返回请求结果，或者在发送失败时为null
	 */
	public static function add_rules (array $rules)
	: ?string
	{
		$request = "add:";
		foreach ($rules as $name => $pattern) {

			// 删除开始和结束的斜杠
			$pattern = preg_replace('/^\/|\/$/', '', $pattern);
			// 将 \d\+ 替换为 \d+
			$pattern = str_replace('\d\+', '\d+', $pattern);
			$request .= $name . "=>" . $pattern . ",";
		}
		var_dump($request);
		return self::send_request($request);
	}

	public static function add_rule ($rule)
	: ?string
	{
		$request = "add:";
		foreach ($rule as $name => $pattern) {

			// 删除开始和结束的斜杠
			$pattern = preg_replace('/^\/|\/$/', '', $pattern);
			// 将 \d\+ 替换为 \d+
			$pattern = str_replace('\d\+', '\d+', $pattern);
			$request .= $name . "=>" . $pattern;
		}
		return self::send_request($request);
	}

	public static function get_rules ()
	: ?string
	{
		$request = "list:";
		return self::send_request($request);
	}
}