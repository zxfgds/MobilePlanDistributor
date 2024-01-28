<?php

namespace app\command;

use app\logic\RegionLogic;
use app\logic\SettingLogic;
use app\model\Region;
use app\service\Map\MapService;
use RedisException;
use support\Db;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;


class InitRegion extends Command
{
	protected static $defaultName        = 'init:region';
	protected static $defaultDescription = 'InitRegion';

	/**
	 * @return void
	 */
	protected function configure ()
	{
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int
	 * @throws RedisException
	 */
	protected function execute (InputInterface $input, OutputInterface $output)
	: int
	{
		Region::truncate();
		$mapConfig = SettingLogic::get('map', 'tencent');
		$service   = (new MapService($mapConfig))->create('tencent');
		$provinces = $service->district();
		Db::beginTransaction();
		foreach ($provinces as $province) {
			// 拼装
			try {
				$provinceData = $this->buildCityData($province);
			} catch (\Exception $e) {
				sleep(1);
				$provinceData = $this->buildCityData($province);
			}

			Region::insert($provinceData);

			try {
				$cityArray = $service->district($province['id']);
			} catch (\Exception $e) {
				sleep(1);
				$cityArray = $service->district($province['id']);
			}

			foreach ($cityArray as $city) {
				$cityData = $this->buildCityData($city, $province['id'], 1);
				Region::insert($cityData);
				try {
					$districtArray = $service->district($city['id']);
				} catch (\Exception $e) {
					sleep(1);
					$districtArray = $service->district($city['id']);
				}

				foreach ($districtArray as $district) {
					$districtData = $this->buildCityData($district, $city['id'], 2);
					Region::insert($districtData);
				}
			}
		}
		Db::commit();

		RegionLogic::provinceCodeMap(1);
		RegionLogic::cityCodeMap(1);
		RegionLogic::districtCodeMap(1);
		return self::SUCCESS;
	}


	protected function buildCityData ($data, $parent = 0, $level = 0)
	: array
	{
		try {
			return [
				'name'       => $data['fullname'],
				'short_name' => $data['name'] ?? $data['fullname'],
				'code'       => $data['id'],
				'level'      => $level,
				'pinyin'     => empty($data['pinyin']) ? '' : implode($data['pinyin']),
				'parent'     => $parent,
			];
		} catch (\Exception  $e) {
			return [];
		}
	}
}
