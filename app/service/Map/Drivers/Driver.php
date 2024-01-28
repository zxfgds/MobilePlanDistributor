<?php

namespace app\service\Map\Drivers;

class Driver
{
	protected array  $config;
	protected string $url;
	
	/**
	 * @param array $config
	 */
	public function __construct(array $config)
	{
		$this->config = $config;
	}
}