<?php

namespace app\controller;

use app\library\DownloadHelper;
use app\library\ImageProcessor;
use app\library\RegexClient;

class TestController extends Controller
{
	public function test ()
	{
		$client = new RegexClient();
// 匹配号码
		$number = "16888888131419841211";
		$result = $client->matchNumber($number);

		return $this->success($result);
	}

	protected function getPhone ()
	{
	}
}