<?php

namespace app\controller\admin;

use app\logic\PhoneNumberPatternLogic;
use Exception;


class PhoneNumberPatternController extends Controller
{
	protected string $logic = PhoneNumberPatternLogic::class;

	/**
	 * 获取匹配列表
	 *
	 * @return false|string 匹配列表
	 * @throws Exception
	 */
	public function getMatcherList ()
	: bool|string
	{
		// 获取电话号码模式列表
		$patterns = PhoneNumberPatternLogic::getList([], 1000);

		// 获取模式列表
		$list = $patterns['list'];

		// 定义数据
		$data = [];

		// 遍历模式列表
		foreach ($list as $item) {
			// 定义数组
			$array = [
				'name'    => $item['name'],
				'weight'  => mt_rand(0, 10000),
				'pattern' => (string)$item['pattern'],
			];

			// 将该数组追加到$data数组中
			$data[] = $array;
		}

		return json_encode($data);
	}

}