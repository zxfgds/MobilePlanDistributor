<?php

namespace app\logic;

use app\exception\CustomException;
use app\library\RedisCache;
use Exception;
use RedisException;
use support\Log;

class BaseLogic
{
	protected static string $model;
	protected static bool   $useCache = FALSE;
	
	/**
	 * @throws Exception
	 */
	protected static function checkModel(): void
	{
		if (!isset(static::$model)) {
			throw new Exception('No model defined in Logic class');
		}
	}
	
	
	/**
	 * @param array  $conditions
	 * @param int    $pageSize
	 * @param int    $page
	 * @param string $sortBy
	 * @param string $sortOrder
	 *
	 * @return array
	 * @throws Exception
	 */
	
	public static function getList(array $conditions = [], int $pageSize = 20, int $page = 1, string $sortBy = 'id', string $sortOrder = 'asc'): array
	{
		static::checkModel();
		$model = new static::$model();
		$query = $model->query();
		if (!empty($conditions)) {
			
			// 判断 $condition 的状态, 是否为一维数组 ( 是否直接单独的条件)
			foreach ($conditions as $key => $condition) {
				
				if (is_array($condition)) {
					$condition = static::preprocessCondition($condition);
				} else {
					$condition = static::preprocessCondition([$key, $condition]);
				}
				if (count($condition) == 2) {
					if (empty($condition[1]) && $condition[1] != 0) continue;
					$query->where($condition[0], $condition[1]);
				} else if (count($condition) == 3) {
					if (empty($condition[2])) continue;
					$query->where($condition[0], $condition[1], $condition[2]);
				}
			}
		}
		
		$total = $query->count();
		$data  = $query->orderBy($sortBy, $sortOrder)->skip(($page - 1) * $pageSize)->take($pageSize)->get()->toArray();
		
		$formattedData = array_map([static::class, 'format'], $data);
		
		return [
			'list'      => $formattedData,
			'total'     => $total,
			'pageSize'  => $pageSize,
			'page'      => $page,
			'last_page' => ceil($total / $pageSize),
		];
	}
	
	
	/**
	 * 客户端获取数据
	 *
	 * @param array  $conditions
	 * @param int    $pageSize
	 * @param int    $page
	 * @param string $sortBy
	 * @param string $sortOrder
	 *
	 * @return array
	 */
	public static function clientGetList(array $conditions = [], int $pageSize = 20, int $page = 1, string $sortBy = 'id', string $sortOrder = 'asc'): array
	{
		try {
			return static::getList($conditions, $pageSize, $page, $sortBy, $sortOrder);
		} catch (Exception $e) {
			return [];
		}
	}
	
	/**
	 * 客户端获取单条数据
	 *
	 * @param array|int $idOrCondition
	 *
	 * @return array|null
	 * @throws Exception
	 */
	public static function clientGetOne(array|int $idOrCondition): ?array
	{
		try {
			return static::getOne($idOrCondition);
		} catch (RedisException $e) {
			Log::error($e);
			return [];
		}
	}
	
	
	/**
	 * Get a single record from the database by ID or other conditions.
	 *
	 * @param array|int $idOrCondition
	 *
	 * @return array|null The formatted record data, or null if the record is not found.
	 * @throws RedisException
	 * @throws Exception
	 */
	public static function getOne(array|int $idOrCondition): ?array
	{
		static::checkModel();
		
		$model = new static::$model();
		
		if (is_int($idOrCondition)) {
			if (static::$useCache) {
				$cachedData = RedisCache::get(static::$model . "_" . $idOrCondition);
				if ($cachedData) {
					return static::format($cachedData);
				}
			}
			
			$data = $model->where('id', $idOrCondition)->first();
			if (empty($data)) throw new Exception('不存在');
			$data = $data->toArray();
			if (!$data) {
				throw new Exception("不存在");
			}
			
			$formattedData = static::format($data);
			
			if (static::$useCache) {
				RedisCache::forever(static::$model . "_" . $idOrCondition, $formattedData);
			}
			
			return $formattedData;
		} else if (is_array($idOrCondition)) {
			$data = $model->where($idOrCondition)->first();
			if (!$data) {
				return NULL;
			}
			return static::format($data);
		} else {
			throw new Exception('Invalid argument type for getOne method');
		}
	}
	
	
	/**
	 * 创建
	 *
	 * @param $data
	 *
	 * @return bool|int
	 * @throws CustomException
	 * @throws Exception
	 */
	public static function create($data): bool|int
	{
		static::checkModel();
		$model = new static::$model();
		$data  = static::formatSaveData($data);
		try {
			return $model->insertGetId($data);
		} catch (CustomException $e) {
			Log::error($e);
			throw new CustomException(env("APP_DEBUG") ? $e->getMessage() : "保存失败");
		}
	}
	
	/**
	 * @param int|array|string $idOrCondition
	 * @param array            $data
	 *
	 * @return true
	 * @throws Exception
	 */
	public static function modify(int|array|string $idOrCondition, array $data): bool
	{
		
		static::checkModel();
		$model = new static::$model();
		
		$data = static::formatSaveData($data);
		
		try {
			if (is_int($idOrCondition)) {
				$model->where('id', $idOrCondition)->update($data);
				static::cacheClear($idOrCondition);
			} else if (is_string($idOrCondition)) {
				$ids = explode(',', $idOrCondition);
				$model->whereIn('id', $ids)->update($data);
				foreach ($ids as $id) {
					static::cacheClear($id);
				}
			} else if (is_array($idOrCondition)) {
				// todo: 查询两次库,直接get 是否 出现  fillable需求
				$ids = $model->where($idOrCondition)->pluck('id')->toArray();
				$model->where($idOrCondition)->update($data);
				foreach ($ids as $id) {
					static::cacheClear($id);
				}
			} else {
				throw new Exception('传入条件错误');
			}
			
			return TRUE;
		} catch (\Exception $e) {
			Log::error($e);
			throw new Exception(env("APP_DEBUG") ? $e->getMessage() : "保存失败");
		}
		
	}
	
	
	/**
	 * 删除记录
	 *
	 * @param $ids
	 *
	 * @return bool
	 * @throws RedisException
	 * @throws Exception
	 */
	public static function delete($ids): bool
	{
		static::checkModel();
		
		$model = new static::$model();
		
		$idArray = explode(',', $ids);
		
		if (empty($idArray)) return FALSE;
		
		foreach ($idArray as $id) {
			if (empty($id)) continue;
			static::beforeDelete($id);
			if ($model->where('id', $id)->delete()) {
				static::afterDelete($id);
			}
		}
		return TRUE;
	}
	
	/**
	 * 格式化数据
	 *
	 * @param $data
	 *
	 * @return array
	 */
	protected static function format($data): array
	{
		
		if (is_object($data)) $data = $data->toArray();
		$formattedData = is_array($data) ? $data : jsonDecode($data);
		foreach ($formattedData as $key => $value) {
			$formattedData[$key] = jsonDecode($value);
		}
		return $formattedData;
	}
	
	/**
	 * 客户端单条数据格式化
	 *
	 * @param $data
	 *
	 * @return array
	 */
	protected static function clientFormat($data): array
	{
		return static::format($data);
	}
	
	/**
	 * 入库前格式化
	 *
	 * @param      $data
	 * @param bool $isEdit
	 *
	 * @return array
	 */
	protected static function formatSaveData($data, bool $isEdit = FALSE): array
	{
		foreach ($data as $key => $value) {
			$data[$key] = is_array($value) ? toJson($value) : $value;
		}
		return $data;
	}
	
	protected static function beforeDelete($data): void
	{
		// Do something before deleting the data
	}
	
	/**
	 * @param $id
	 *
	 * @return void
	 * @throws RedisException
	 */
	protected static function afterDelete($id): void
	{
		static::cacheClear($id);
	}
	
	
	/**
	 * 将传入的数据与默认数据合并，并将结果赋值回传入的参数 `$data` 中。
	 *
	 * @param array $data        需要合并的数据，函数将直接修改该参数；
	 * @param array $defaultData 默认的数据模板。
	 *
	 * @return array
	 */
	public static function setDefaultData(array $data, array $defaultData = []): array
	{
		
		$defaultData = static::defaultData() ?? [];
		
		if (empty($defaultData)) return $data;
		
		foreach ($defaultData as $key => $defaultValue) {
			// 如果 `$data` 中对应的值为空，则使用默认值；
			if (empty($data[$key])) {
				$data[$key] = $defaultValue;
			} else {
				// 如果默认值是数组，则递归进行合并操作；
				if (is_array($defaultValue)) {
					$dataValue = $data[$key];
					foreach ($defaultValue as $k => $v) {
						if (empty($dataValue[$k])) {
							$dataValue[$k] = $v;
						}
					}
					$data[$key] = $dataValue;
				}
			}
		}
		return $data;
	}
	
	/**
	 * 生成默认数据模板
	 * @return array
	 */
	public static function defaultData(): array
	{
		return [];
	}
	
	/**
	 * 清除缓存
	 *
	 * @param $idOrOtherPrimaryKey
	 *
	 * @return void
	 * @throws RedisException
	 */
	public static function cacheClear($idOrOtherPrimaryKey): void
	{
		RedisCache::forget(static::$model . '_' . $idOrOtherPrimaryKey);
	}
	
	
	/**
	 * 预处理查询条件
	 *
	 * @param array $condition 单条查询条件
	 *
	 * @return array 预处理后的查询条件
	 */
	public static function preprocessCondition(array $condition): array
	{
		// 定义允许的运算符
		$allowedOperators = [
			'equal'     => '=',
			'gt'        => '>',
			'lt'        => '<',
			'nlt'       => '>=',
			'ngt'       => '<=',
			'like'      => 'like',
			'startWith' => 'like',
			'endWith'   => 'like',
			'date'      => 'date',
			'month'     => 'month',
			'year'      => 'year',
			'day'       => 'day',
			'in'        => 'in',
			'notIn'     => 'notIn',
			'between'   => 'between',
			'findInset' => 'findInset',
			'json'      => 'json',
		];
		
		// 如果条件只有两个元素，则返回 [$key, $value]
		if (count($condition) === 2) {
			return $condition;
		}
		
		// 如果条件有三个元素，则第二个元素为运算符
		$operator = $condition[1];
		if (in_array($operator, array_keys($allowedOperators))) {
			$operator = $allowedOperators[$operator];
			if ($operator === 'like') {
				$value = $condition[2];
				$value = match ($condition[1]) {
					'startWith' => "{$value}%",
					'endWith' => "%{$value}",
					default => "%{$value}%",
				};
				return [$condition[0], $operator, $value];
			}
			
			if (in_array($operator, ['date', 'month', 'year', 'day', 'in', 'notIn'])) {
				return [$condition[0], $operator, $condition[2]];
			}
			
			return [$condition[0], $operator, $condition[2]];
		}
		
		// 如果是 between，则需要判断 $value 是否是二元数组
		if ($operator === 'between' && is_array($condition[2]) && count($condition[2]) === 2) {
			return [$condition[0], $operator, $condition[2]];
		}
		
		// 其他情况忽略该条件
		return [];
	}
	
}
