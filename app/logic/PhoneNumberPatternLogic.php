<?php

namespace app\logic;

use app\library\RedisCache;
use app\model\PhoneNumberPattern;
use Illuminate\Support\Arr;
use RedisException;

class PhoneNumberPatternLogic extends BaseLogic
{
	protected static string $model = PhoneNumberPattern::class;

	public static function modify (int|array|string $idOrCondition, array $data)
	: bool
	{
		return parent::modify($idOrCondition, $data); // TODO: Change the autogenerated stub
	}

	public static function clientGetList (array $conditions = [], int $pageSize = 20, int $page = 1, string $sortBy = 'id', string $sortOrder = 'asc')
	: array
	{
		$conditions['status'] = TRUE;
		// params type: 0 普通/所有 , 1: number_select
		$type = Arr::pull($conditions, 'type');

		if ($type === 1) $conditions["select_num_status"] = TRUE;
		$data = parent::clientGetList($conditions, $pageSize, $page, $sortBy, $sortOrder); // TODO: Change the autogenerated stub
		$list = Arr::pluck($data['list'], 'name');
		return ['list' => $list, 'total' => $data['total']];
	}

	/**
	 * Get all phone number patterns.
	 *
	 * @return array All phone number patterns.
	 * @throws RedisException
	 */
	public static function getAll ()
	: array
	{
		// Cache name for phone number patterns.
		$cacheName = 'phone_number_patterns';

		// Get phone number patterns from Redis cache.
		$patterns = RedisCache::get($cacheName);

		// If no patterns in Redis cache, fetch patterns from database and set them into Redis cache.
		if (empty($patterns)) {
			// Fetch all patterns from database.
			$data = static::$model::all();

			// Loop through each pattern and add it to the patterns array.
			foreach ($data as $pattern) {
				$patterns[$pattern->name] = (string)$pattern->pattern; // 此处一定要强制转换一下
			}

			// Store the patterns array into Redis cache.
			RedisCache::forever($cacheName, $patterns);
		}

		// Return all phone number patterns.
		return $patterns;
	}


	public static function cacheClear ($idOrOtherPrimaryKey)
	: void
	{
		$cacheName = 'phone_number_patterns';
		RedisCache::forget($cacheName);
		parent::cacheClear($idOrOtherPrimaryKey); // TODO: Change the autogenerated stub
	}
}