<?php

namespace app\service\Map\Drivers;

use app\service\Map\Drivers\Driver;

class Amap extends Driver implements MapInterface
{
	protected string $url = 'https://restapi.amap.com';
	
	public function ip2region($ip)
	: array {
		$path     = '/v3/ip';
		$params   = ['key' => $this->config['key'], 'ip' => $ip];
		$response = file_get_contents($this->url . $path . '?' . http_build_query($params));
		return $response;
	}
	
	public function district($code = NULL)
	: array {
		$path   = '/config/district';
		$params = ['key' => $this->config['key']];
//		if ($code) $params['a]
	}
}