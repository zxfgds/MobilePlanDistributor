<?php

namespace app\controller\http;

use app\logic\ClientConfigLogic;
use app\service\Map\MapService;

class ClientConfigController extends Controller
{
	protected string $logic = ClientConfigLogic::class;
	
	public function get()
	{
		//		$service = new MapService()
		return $this->success([
			'config' => ClientConfigLogic::getConfig(),
		]);
	}
}