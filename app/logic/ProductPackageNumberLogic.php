<?php
	
	namespace app\logic;
	
	use app\library\EsSearch;
	use app\model\Number;
	use app\model\PhoneNumberStore;
	use app\model\ProductPackageNumber;
	use app\service\OperatorApi\Service;
	use EasyWeChat\Kernel\Exceptions\Exception;
	use Elastic\Elasticsearch\Exception\AuthenticationException;
	use Elastic\Elasticsearch\Exception\ClientResponseException;
	use Elastic\Elasticsearch\Exception\MissingParameterException;
	use Elastic\Elasticsearch\Exception\ServerResponseException;
	use Illuminate\Support\Arr;
	use RedisException;
	use ReflectionException;
	use support\Log;
	
	class ProductPackageNumberLogic
	{
		
		
		protected static int $productType = Number::TYPE_PACKAGE;
		
		
		/**
		 * @return void
		 */
		public static function truncate(): void
		{
			ProductPackageNumber::truncate();
			$esIndex = PhoneNumberLogic::getIndexName(static::$productType);
			try {
				EsSearch::use($esIndex)->deleteIndex();
			} catch (\Exception $e) {
				Log::error($e->getMessage());
			}
		}
		
		/**
		 * Get list of available phone numbers
		 *
		 * @param array $params Array of parameters to filter the phone number list
		 *
		 * @return array Array of available phone numbers
		 *
		 * @throws AuthenticationException
		 * @throws ClientResponseException
		 * @throws Exception If the required parameter `packageId` is missing or invalid
		 * @throws MissingParameterException
		 * @throws RedisException If there is an error with the Redis database
		 * @throws ReflectionException If there is an error with the PHP reflection API
		 * @throws ServerResponseException
		 */
		public static function getList(array $params): array
		{
			$packageId = Arr::pull($params, 'packageId');
			if (empty($packageId)) {
				throw new Exception('缺少必要参数 packageId');
			}
			
			$package = ProductPackageLogic::getOne($packageId);
			if (empty($package)) {
				throw new Exception('套餐不存在');
			}
			
			$selectNumStatus = $package['select_num_status'];
			$config          = $package['select_num_config'];
			
			// 未开启选号
			if (!$selectNumStatus) {
				return [];
			}
			
			// 本地号库选号
			if ($config['type'] === 0) {
				return PhoneNumberLogic::getEsList($params, PhoneNumberStore::TYPE_PACKAGE, $config['store_id']);
			}
			
			// 运营商接口取号
			return static::getNumberFromApi($packageId, $params);
		}
		
		/**
		 * @throws RedisException
		 * @throws ReflectionException
		 */
		public static function getNumberFromApi($packageId, array $params = []): array
		{
			$package   = ProductPackageLogic::getOne($packageId);
			$apiConfig = $package['remote_api_config'];
			$api       = OperatorApiLogic::getOne($apiConfig['api_id']);
			
			$service = (new Service($api['class_name']))->getNumber($params);
			
		}
	}