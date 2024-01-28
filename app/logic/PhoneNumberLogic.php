<?php
	
	namespace app\logic;
	
	use app\library\EsSearch;
	use app\library\RegexClient;
	use app\logic\PhoneNumberTraits\EsTrait;
	use app\logic\PhoneNumberTraits\ImportTrait;
	use Elastic\Elasticsearch\Exception\AuthenticationException;
	use Elastic\Elasticsearch\Exception\ClientResponseException;
	use Elastic\Elasticsearch\Exception\MissingParameterException;
	use Elastic\Elasticsearch\Exception\ServerResponseException;
	use Exception;
	use InvalidArgumentException;
	use support\Log;
	
	class PhoneNumberLogic
	{
		use ImportTrait;
		use EsTrait;
		
		
		/**
		 * 获取ES列表
		 *
		 * @param array $params 请求参数
		 * @param string $storeType 商店类型
		 * @param int $storeId 商店ID，默认为0
		 * @param bool $singleResult
		 * @return array|NULL 返回ES查询结果数组
		 *
		 * @throws AuthenticationException
		 * @throws ClientResponseException 当ES返回了4xx响应代码时抛出异常
		 * @throws MissingParameterException
		 * @throws ServerResponseException 当ES返回了5xx响应代码时抛出异常
		 */
		public static function getEsList(array $params, string $storeType, int $storeId = 0, bool $singleResult = false): array|null
		{
			// 设置参数默认值
			$params = array_merge(['sortBy' => $params['sortBy'] ?? 'timestamp', 'sort' => $params['sort'] ?? 'desc', 'page' => $singleResult ? 1 : ($params['page'] ?? 1), 'pageSize' => $singleResult ? 1 : ($params['pageSize'] ?? 10)], $params);
			
			$es = static::buildEsQuery($params, $storeType, $storeId);
			// 返回ES
			return $singleResult ? $es->first() : $es->orderBy($params['sortBy'], $params['sort'])->paginate($params['page'], $params['pageSize']);
		}
		
		public static function getOne(array $params, string $storeType, int $storeId = 0)
		{
			
			try {
				// 参数验证
				static::validateParams($params);
				$es  = static::buildEsQuery($params, $storeType, $storeId);
				$res = $es->first();
				return $res ? $res['number'] : NULL;
			} catch (Exception $e) {
				return NULL;
			}
		}
		
		/**
		 * @param $number
		 * @return array
		 */
		public static function getRegex($number): array
		{
			try {
				$client = new RegexClient();
				return $client->matchNumber($number) ?? [];
			} catch (Exception $e) {
				return [];
			}
		}
		
		
		/**
		 * @param $number
		 * @param $type
		 * @return array|bool
		 */
		public static function getAdjacent($number, $type): bool|array
		{
			try {
				$pre  = static::getEsList(['number' => $number - 1], $type, 0, true);
				$next = static::getEsList(['number' => $number + 1], $type, 0, 1);
				return ['prev' => $pre['number'], 'next' => $next['number']];
			} catch (\Exception $e) {
				return ['prev' => null, 'next' => null];
			}
		}
		
		
		/**
		 * @param array $params
		 * @param string $storeType
		 * @param int $storeId
		 * @return EsSearch
		 * @throws AuthenticationException
		 * @throws ClientResponseException
		 * @throws MissingParameterException
		 * @throws ServerResponseException
		 */
		private static function buildEsQuery(array $params, string $storeType, int $storeId = 0): EsSearch
		{
			// 获取索引名称
			$index = static::getIndexName($storeType);
			
			// 设置默认排序方式、默认分页、及相关变量
//			$defaultSortBy    = 'timestamp';
//			$defaultSortOrder = 'desc';
//			$defaultPage      = 1;
//			$defaultPageSize  = 10;
			
			// 获取排序方式、排序顺序、页码、每页数量、匹配模式等参数
//			$sortBy    = $params['sortBy'] ?? $defaultSortBy;
//			$sortOrder = $params['sort'] ?? $defaultSortOrder;
//			$page      = $params['page'] ?? $defaultPage;
//			$pageSize  = $params['pageSize'] ?? $defaultPageSize;
			$pattern = $params['pattern'] ?? NULL;
			
			// 如果存在匹配模式，则使用 PhoneNumberPatternLogic::getOne() 获取电话号码模式
			if ($pattern) {
				try {
					$pattern = PhoneNumberPatternLogic::getOne($pattern);
				} catch (Exception $e) {
					// 如果发生异常，则记录错误并将模式设置为null
					Log::error($e);
					$pattern = NULL;
				}
			}
			// 设置条件数组
			$conditions = [
				'status'              => $params['status'] ?? 1,
				'province_code'       => $params['region']['province_code'] ?? 0,
				'city_code'           => $params['region']['city_code'] ?? 0,
				'keyword'             => $params['keyword'] ?? NULL,
				'keyword_is_end'      => $params['keyword_is_end'] ?? FALSE,
				'keyword_by_position' => $params['keyword_by_position'] ?? [],
				'pattern'             => $pattern ? $pattern['pattern'] : NULL,
				'number'              => $params['number'] ?? null,
			];
			
			
			// 创建 ES 查询实例
			$es = EsSearch::use($index);
			
			// 如果存在商        店ID，则添加其限制条件
			if ($storeId) {
				$es->where('store_id', $storeId);
			}
			
			// 如果存在省份代码，则添加其限制条件
			if (!empty($conditions['province_code'])) {
				$es->where('province_code', $conditions['province_code']);
			}
			
			// 如果存在城市代码，则添加其限制条件
			if (!empty($conditions['city_code'])) {
				$es->where('city_code', $conditions['city_code']);
			}
			
			// 如果存在状态限制，则添加其限制条件
			if (!empty($conditions['status'])) {
				$es->where('status', $conditions['status']);
			}
			
			// 如果存在关键字，则添加其限制条件
			if (!empty($conditions['keyword'])) {
				if ($conditions['keyword_is_end']) {
					$es->where('number', ['fuzzy_end' => $conditions['keyword']]);
				} else {
					$es->where('number', ['fuzzy' => $conditions['keyword']]);
				}
			}
			
			// 如果存在电话号码模式，则添加其限制条件
			if (!empty($conditions['pattern'])) {
				$es->where('number', ['fixed_regexp' => $conditions['pattern']]);
			}
			
			// 如果存在关键字位置，则添加其限制条件
			if (!empty($conditions['keyword_by_position'])) {
				$arr = array_filter($conditions['keyword_by_position'], function ($value) {
					return !empty($value) || $value === 0;
				});
				$es->where('number', ['position' => $arr]);
			}
			
			// 如果存在号码
			if (!empty($conditions['number'])) {
				$es->where('number', $conditions['number']);
			}
			
			return $es;
		}
		
		
		private static function validateParams(array $params): void
		{
			//对分页参数、排序方式和排序顺序的有效性进行检查
			
			// 检查是否设置了page参数，如果设置了，则检查是否为数字，且大于等于1
			if (isset($params['page']) && (!is_numeric($params['page']) || $params['page'] < 1)) {
				throw new InvalidArgumentException('Invalid page parameter');
			}
			
			// 检查是否设置了pageSize参数，如果设置了，则检查是否为数字，且大于等于1
			if (isset($params['pageSize']) && (!is_numeric($params['pageSize']) || $params['pageSize'] < 1)) {
				throw new InvalidArgumentException('Invalid pageSize parameter');
			}
			
			// 添加其他有效的排序字段
			$validSortBy = ['timestamp'];
			if (isset($params['sortBy']) && !in_array($params['sortBy'], $validSortBy)) {
				throw new InvalidArgumentException('Invalid sortBy parameter');
			}
			
			// 检查是否设置了sort参数，如果设置了，则检查其值是否为asc或desc
			$validSortOrder = ['asc', 'desc'];
			if (isset($params['sort']) && !in_array($params['sort'], $validSortOrder)) {
				throw new InvalidArgumentException('Invalid sort parameter');
			}
		}
		
	}