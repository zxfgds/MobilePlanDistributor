<?php

namespace app\service\Map;

class MapService
{
	protected array $config;
	
	/**
	 * @param array $config
	 */
	public function __construct(array $config)
	{
		$this->config = $config;
	}
	
	public function create($driver)
	{
		$driver = ucfirst($driver);
		$class  = __NAMESPACE__ . "\\Drivers\\{$driver}";
		if (!class_exists($class)) {
			throw new \InvalidArgumentException("Drivers [{$driver}] not supported.");
		}
		return new $class($this->config);
	}
}