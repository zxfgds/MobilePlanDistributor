<?php

namespace app\command;

use app\library\RegexClient;
use app\logic\SettingLogic;
use app\service\Map\MapService;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class TestCommand extends Command
{
	
	
	protected static $defaultName        = 'test:test';
	protected static $defaultDescription = 'TestCommand';
	
	
	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 *
	 * @return int
	 * @throws Exception
	 */
	#[NoReturn] protected function execute(InputInterface $input, OutputInterface $output)
	: int {
		$mapConfig = SettingLogic::get('map', 'tencent');
		$service   = (new MapService($mapConfig))->create('tencent');
		
		$res = $service->ip2region("122.6.84.242");
		var_dump($res);
		return TRUE;
	}
	
}
