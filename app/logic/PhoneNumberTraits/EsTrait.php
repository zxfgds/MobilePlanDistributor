<?php

namespace app\logic\PhoneNumberTraits;

use app\library\EsSearch;
use app\model\PhoneNumberStore;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Exception;

trait EsTrait
{
    
    /**
     * 检查索引是否存在，如果不存在则可选择创建
     *
     * @param int  $storeType 存储类型
     * @param bool $create    是否创建索引，若为 true 则会尝试创建索引
     *
     * @return bool|string 是否成功检查或创建索引
     *
     * @throws AuthenticationException 如果访问 Elasticsearch 时发生身份验证错误
     * @throws ClientResponseException 如果 Elasticsearch 返回 4xx 响应代码
     * @throws MissingParameterException 如果未提供所需的参数
     * @throws ServerResponseException 如果 Elasticsearch 返回 5xx 响应代码
     */
    public static function checkIndex(int $storeType, bool $create = FALSE): bool|string
    {
        // 获取索引名称，并判断索引是否已存在
        $indexName = static::getIndexName($storeType);
        $exists    = EsSearch::indexExists($indexName);
        // 索引已存在则返回 true
        if ($exists) {
            return $indexName;
        }
        // 索引不存在且不需要创建，则返回 false
        if (!$create) {
            return FALSE;
        }
        try {
            // 尝试创建索引
            static::createIndex($indexName, $storeType);
            return $indexName;
        } catch (Exception $e) {
            // 创建索引失败，则返回 false
            return FALSE;
        }
    }
    
    /**
     * @param $storeType
     *
     * @return array|string
     */
    public static function getEsIndexId($storeType): array|string
    {
        if ($storeType === PhoneNumberStore::TYPE_PACKAGE) {
            return ['number', 'store_id'];
        }
        return "number";
    }
    
    
    public static function checkDocExists($data, $storeType): ?array
    {
        $index = static::getIndexName($storeType);
        
        $conditions['number'] = $data['number'];
        if ($storeType === PhoneNumberStore::TYPE_PACKAGE) {
            $conditions['store_id'] = $data['store_id'];
        }
        
        return EsSearch::use($index)->where($conditions)->first();
    }
    
    /**
     * 创建索引
     *
     * @param string $indexName 索引名称
     * @param int    $storeType 存储类型
     *
     * @throws Exception 当创建失败时抛出异常
     */
    public static function createIndex(string $indexName, int $storeType): void
    {
        // 如果存储类型是 [PhoneNumberStore::TYPE_FANCY, PhoneNumberStore::TYPE_PACKAGE, PhoneNumberStore::TYPE_NORMAL] 中的任何一个，则指定索引属性
        $properties = [];
        if (in_array($storeType, [PhoneNumberStore::TYPE_FANCY, PhoneNumberStore::TYPE_PACKAGE, PhoneNumberStore::TYPE_NORMAL])) {
            $properties = [
                'number'        => ['type' => 'keyword'],
                'operator'      => ['type' => 'integer'],
                'shop_id'       => ['type' => 'integer'],
                'store_id'      => ['type' => 'integer'],
                'province'      => ['type' => 'keyword'],
                'city'          => ['type' => 'keyword'],
                'province_code' => ['type' => 'integer'],
                'city_code'     => ['type' => 'integer'],
                'status'        => ['type' => 'integer'],
                'price'         => ['type' => 'float'],
                'brand'         => ['type' => 'keyword'],
                'package'       => ['type' => 'text'],
                'timestamp'     => [
                    'type'   => 'date',
                    'format' => 'epoch_millis',
                ],
            ];
        }
        
        // 调用 EsSearch 类的 createIndex 方法创建索引
        EsSearch::createIndex($indexName, $properties);
    }
    
    /**
     * 根据不同类型获取对应的索引名称
     *
     * @param int $type 号码类型 (可选值: 'fancy', 'package', 'normal')
     *
     * @return string 返回对应的索引名称 (如果$type为null或不在可选值中，则默认返回'phone_number_normal')
     */
    public static function getIndexName(int $type): string
    {
        if ($type === PhoneNumberStore::TYPE_FANCY) {
            return "phone_number_fancy";
        }
        if ($type === PhoneNumberStore::TYPE_PACKAGE) {
            return "phone_number_package";
        }
        if ($type === PhoneNumberStore::TYPE_NORMAL) {
            return "phone_number_normal";
        }
        
        return $type ?? "phone_number_normal";
    }
}