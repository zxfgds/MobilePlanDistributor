<?php
	
	namespace app\logic;
	
	use app\library\RedisCache;
	use app\model\NumberBrand;
	use Redis;
	use RedisException;
	
	class NumberBrandLogic extends BaseLogic
	{
		protected static string $model = NumberBrand::class;
		
		
		/**
		 * @param bool $forceRebuild
		 * @return array|mixed|Redis|string
		 * @throws RedisException
		 */
		public static function getBrandsNameIdMap(bool $forceRebuild = FALSE): mixed
		{
			$cacheKey = 'brands_name_code_map';
			// 尝试从缓存中获取数据
			$brandsNameIdMap = RedisCache::get($cacheKey);
			// 如果强制重建或缓存不存在
			if ($forceRebuild || empty($brandsNameIdMap)) {
				// 从数据库中获取所有区域数据
				$brands = NumberBrand::all();
				// 构建 regions_name_code_map 缓存数据
				$brandsNameIdMap = [];
				foreach ($brands as $brand) {
					$brandsNameIdMap[$brand->name] = $brand->id;
				}
				// 将 brands_name_id_map 缓存数据存储到 RedisCache
				RedisCache::forever($cacheKey, $brandsNameIdMap);
			}
			
			return $brandsNameIdMap;
		}
	}