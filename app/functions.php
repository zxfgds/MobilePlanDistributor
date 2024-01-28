<?php
	
	/**
	 * 对字符串进行哈希加密
	 *
	 * @param string $string 要加密的字符串
	 *
	 * @return string 返回加密后的字符串
	 */
	function bcrypt(string $string): string
	{
		return \app\library\Hash::make($string);
	}
	
	/**
	 * 获取数据库路径
	 *
	 * @param string|null $path 数据库路径
	 *
	 * @return string 返回数据库路径
	 */
	function databasePath(string $path = NULL): string
	{
		return path_combine(BASE_PATH . DIRECTORY_SEPARATOR . 'database', $path);
	}
	
	/**
	 * 获取存储路径
	 *
	 * @param string|null $path 存储路径
	 *
	 * @return string 返回存储路径
	 */
	function storagePath(string $path = NULL): string
	{
		return path_combine(BASE_PATH . DIRECTORY_SEPARATOR . 'storage/app', $path);
	}
	
	/**
	 * 获取当前时间
	 *
	 * @param int|null $ts 时间戳
	 *
	 * @return string 返回当前时间
	 */
	function now(int $ts = NULL): string
	{
		return $ts ? date("Y-m-d H:i:s", $ts) : date('Y-m-d H:i:s');
	}
	
	
	/**
	 * 解析客户端传来的参数
	 * @return mixed
	 */
	function parseClientParams(): mixed
	{
		// 获取 HTTP 请求头中的 params 参数
		$clientParams = request()->header('params');
		// 如果 params 参数不存在，则使用默认值
		$clientParams = empty($clientParams) ? '{"shop_id":0,"channel_id":0,"form":0,"from_id":0,"platform_id":10}' : $clientParams;
		// 将 JSON 格式的字符串转换为关联数组
		return jsonDecode($clientParams);
	}
	
	/**
	 * 格式化内存大小.
	 *
	 * 返回格式化后的内存大小字符串.
	 */
	function formatMemorySize(): string
	{
		// 确定当前内存使用量
		$size = memory_get_usage();
		
		// 判断内存大小，进行不同的格式化
		if ($size < 1024 * 1024) {  // 小于1MB
			return round($size / 1024, 2) . ' KB';
		} else if ($size < 1024 * 1024 * 1024) {  // 小于1GB
			return round($size / (1024 * 1024), 2) . ' MB';
		} else {  // 大于1GB
			return round($size / (1024 * 1024 * 1024), 2) . ' GB';
		}
	}
	
	/**
	 * 将 JSON 字符串解码成 PHP 数组或对象。
	 *
	 * @param mixed $json 要解码的 JSON 字符串。
	 *
	 * @return mixed 解码后的 PHP 数组或对象，或解码失败时返回原字符串。
	 */
	function jsonDecode(mixed $json): mixed
	{
		if (empty($json)) return $json;
		if (is_array($json)) return $json;
		if (!is_string($json)) return $json;
		
		$decoded = json_decode($json, TRUE);
		return (json_last_error() === JSON_ERROR_NONE) ? $decoded : $json;
	}
	
	/**
	 * 获取微秒时间
	 *
	 * @return float 返回微秒时间
	 */
	function getMicrometer(): float
	{
		[$uSec, $sec] = explode(" ", microtime());
		return ((float)$uSec + (float)$sec);
	}
	
	/**
	 * 检查并创建目录
	 *
	 * 这个函数检查目录是否存在，如果不存在，则创建它。
	 *
	 * @param string $path 要检查和创建的目录路径
	 *
	 * @return void
	 */
	function checkAndCreateDirectory(string $path): void
	{
		// Check if the directory exists
		if (!is_dir($path)) {
			// Get the parent directory's path
			$parent = dirname($path);
			// Recursively check and create the parent directory
			checkAndCreateDirectory($parent);
			// Create the directory with 0775 permissions and allow creation of multiple levels
			mkdir($path, 0775, TRUE);
		}
	}
	
	/**
	 * 根据权重随机选择账号或者配置
	 *
	 * @param array $accounts [账号 => 权重值]的数组
	 *
	 * @return int 选择的账号ID
	 */
	function selectAccountByWeight(array $accounts): int
	{
		$totalWeight = array_sum($accounts['weight']); // 计算总权重
		$random      = mt_rand(1, $totalWeight); // 随机数
		$weight      = 0; // 当前权重
		foreach ($accounts as $key => $account) {
			$weight += $account['weight']; // 加上当前账号的权重
			if ($random <= $weight) { // 如果当前随机数小于等于当前权重，说明选中了这个账号
				return $account; // 返回账号或者配置
			}
		}
		return $accounts[0];
	}
	
	/**
	 * 获取请求的 token
	 *
	 * @return string|null 返回请求的 token，如果没有则返回 null
	 */
	function _token(): string|null
	{
		$token = request()->header('Authorization');
		if (empty($token)) return NULL;
		$token = str_replace('Bearer', '', $token);
		return trim($token);
	}
	
	/**
	 * 将数组或对象转换为 JSON 格式的字符串
	 *
	 * @param mixed $data 待转换的变量
	 * @param int $options JSON 编码选项，默认为 JSON_UNESCAPED_UNICODE
	 * @param int $depth JSON 编码深度，用于防止递归嵌套，默认为 512
	 *
	 * @return string|array|null 如果转换成功则返回 JSON 字符串，否则返回 null
	 */
	function toJson(mixed $data, int $options = JSON_UNESCAPED_UNICODE, int $depth = 512): string|array|null
	{
		// 如果数据为空，则返回 原值
		if (empty($data)) return $data;
		// 如果不是数组或对象，则返回 原值
		if (!is_array($data) && !is_object($data)) return $data;
		// 使用 JSON 编码将数组或对象转换为字符串
		$json = json_encode($data, $options, $depth);
		// 如果转换失败，则返回 原值
		if ($json === FALSE) return $data;
		return $json;
	}
	
	/**
	 * 获取客户端IP
	 * @return string
	 */
	function getClientIp(): string
	{
		// 定义可能包含客户端 IP 的环境变量数组
		$ipSources = [
			'X-REAL-IP',
			'HTTP_CLIENT_IP',
			'HTTP_X_REAL_IP',
			'HTTP_X_FORWARDED_FOR',
			'REMOTE_ADDR',
		];
		
		// 遍历环境变量数组，检查并获取客户端 IP
		foreach ($ipSources as $source) {
			$ip = request()->header($source);
			// 如果获取的是 X-Forwarded-For（HTTP_X_FORWARDED_FOR），取第一个 IP
			if ($source === 'HTTP_X_FORWARDED_FOR') {
				$ips = explode(',', $ip);
				$ip  = trim($ips[0]);
			}
		}
		
		// 如果无法从任何环境变量中获取客户端 IP，则返回默认值
		return $ip ?? '0.0.0.0';
	}
	
	/**
	 * @return int[]|mixed
	 */
	function getRequestQuery(): mixed
	{
		//{"channel_id":0,"shop_id":0,"form":0,"from_id":0,"platform_id":11}
		$queryParams = request()->header('params');
		if (empty($queryParams)) return ['channel_id' => 0, 'shop_id' => 0, 'form' => 0, 'from_id' => 0, 'platform_id' => 11];
		return json_decode($queryParams, TRUE);
	}
