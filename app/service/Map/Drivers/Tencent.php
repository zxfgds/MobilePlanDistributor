<?php

namespace app\service\Map\Drivers;

use app\service\Map\Drivers\Driver;

class Tencent extends Driver implements MapInterface
{
	
	protected string $url = 'https://apis.map.qq.com';
	
	public function ip2region($ip)
	: array {
		$path             = '/ws/location/v1/ip';
		$queryData        = [
			'ip'  => $ip,
			'key' => $this->config['key'],
		];
		$queryData['sig'] = $this->makeSign($path, $queryData);
		
		$response = jsonDecode(file_get_contents($this->url . $path . '?' . http_build_query($queryData)));
		
		$regionData = $response['result']['ad_info'];
		
		return [
			'province' => $regionData['province'],
			'city'     => $regionData['city'],
			'district' => $regionData['district'],
		];
		
	}
	
	/**
	 * 根据行政区划号码获取其子级行政区划信息
	 * @param string|null $code 行政区划号码，默认为null表示获取顶级行政区划
	 * @return array 包含行政区划信息的数组
	 */
	public function district($code = NULL)
	: array {
		// API接口路径
		$path = '/ws/district/v1/getchildren';
		
		// 查询参数中的key
		$queryData = ['key' => $this->config['key'],];
		
		// 如果传入了行政区划号码，则将该参数加入查询参数当中
		if ($code) {
			$queryData['id'] = $code;
		}
		
		// 生成请求签名并将该参数加入查询参数当中
		$queryData['sig'] = $this->makeSign($path, $queryData);
		
		// 生成请求URL
		$url = $this->url . $path . '?' . http_build_query($queryData);
		
		// 获取API响应并解析JSON数据
		$response = jsonDecode(file_get_contents($url));
		
		// 返回响应结果中的第一个元素，该元素包含行政区划信息
		return $response['result'][0];
	}
	
	
	/**
	 * 生成签名
	 *
	 * @param string $path 请求路径
	 * @param array $data 请求参数
	 * @return string 返回生成的签名
	 */
	protected function makeSign(string $path, array $data)
	: string {
		// 对请求参数按照键名进行升序排序
		ksort($data);
		// 构建签名原始字符串，包括请求路径、以及按照键名升序排序后的请求参数、和配置中的 secret_key
		$raw = $path . "?" . http_build_query($data) . $this->config['secret_key'];
		// 使用 md5 进行加密计算
		return md5($raw);
	}
	
}