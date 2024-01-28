<?php

namespace app\service\Map\Drivers;

interface MapInterface
{
	public function ip2region($ip): array;
	
	public function district($code = null): array;
}