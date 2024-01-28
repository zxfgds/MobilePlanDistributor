<?php
	
	namespace app\logic;
	
	use app\library\EsSearch;
	use app\model\Number;
	use app\model\Operator;
	use app\model\PhoneNumberStore;
	use app\model\ProductFancyNumber;
	use Elastic\Elasticsearch\Exception\AuthenticationException;
	use Elastic\Elasticsearch\Exception\ClientResponseException;
	use Elastic\Elasticsearch\Exception\MissingParameterException;
	use Elastic\Elasticsearch\Exception\ServerResponseException;
	
	class ProductFancyNumberLogic extends PhoneNumberLogic
	{
		protected static string $model       = ProductFancyNumber::class;
		protected static int    $productType = Number::TYPE_FANCY;
		
		
		/**
		 * @param array $params
		 * @return array
		 * @throws AuthenticationException
		 * @throws ClientResponseException
		 * @throws MissingParameterException
		 * @throws ServerResponseException
		 */
		public static function getList(array $params = []): array
		{
			$data = PhoneNumberLogic::getEsList($params, PhoneNumberStore::TYPE_FANCY);
			$list = $data['list'];
			foreach ($list as $key => $item) {
				$list[$key]['rules']         = static::getRegex($item['number']);
				$list[$key]['adjacent']      = static::getAdjacent($item['number'], static::$productType);
				$list[$key]['operator_name'] = Operator::OPERATORS[$item['operator']];
			}
			$data['list'] = $list;
			
			return $data;
		}
		
		/**
		 * @return void
		 * @throws AuthenticationException
		 * @throws ClientResponseException
		 * @throws MissingParameterException
		 * @throws ServerResponseException
		 */
		public static function truncate(): void
		{
			ProductFancyNumber::truncate();
			// 调用模型类中的静态方法 truncate 清空数据表
			
			$indexName = PhoneNumberLogic::getIndexName(static::$productType);
			// 从 PhoneNumberLogic 类获取与当前产品类型相对应的索引名称并赋值给 $indexName 变量
			
			EsSearch::use($indexName)->deleteIndex();
			// 使用 EsSearch 类删除指定索引的文档
		}
		
		
		/**
		 * @param $ids
		 * @return bool
		 */
		public static function delete($ids): bool
		{
			return false;
		}
		
		/**
		 * @param $data
		 * @param bool $isEdit
		 * @return array
		 */
		public static function formatSaveData($data, bool $isEdit = FALSE): array
		{
			return $data;
		}
		
	}
