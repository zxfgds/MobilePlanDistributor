<?php

namespace app\service;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Elastic\Elasticsearch\Response\Elasticsearch;
use Http\Promise\Promise;
use Exception;

class ElasticsearchAliasNotDefinedException extends Exception { }

/**
 * Elasticsearch 客户端类
 */
class ElasticsearchService
{
    private Client $client;
    private array  $params = [];
    
    /**
     * 构造函数，初始化 Elasticsearch 客户端
     *
     * @param string|null $alias 要操作的索引别名
     *
     * @throws AuthenticationException
     * @throws ElasticsearchAliasNotDefinedException
     */
    public function __construct(string $alias = NULL)
    {
        if (!$alias) {
            throw new ElasticsearchAliasNotDefinedException('Elasticsearch alias is not defined.');
        }
        
        $hosts        = ['localhost:9200'];
        $params       = ['hosts' => $hosts, 'index' => $alias];
        $this->client = ClientBuilder::create()->setHosts($hosts)->build();
        $this->params = $params;
    }
    
    /**
     * 将数据插入 Elasticsearch
     *
     * @param array $data 要插入的数据
     *
     * @return Elasticsearch|Promise
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function insert(array $data): Elasticsearch|Promise
    {
        $index = $this->params['index'];
        
        $doc['body'] = $data;
        if ($index === 'virtual_phone_numbers') {
            // 保证 phone_number 唯一性
            $doc['id'] = $data['phone_number'];
        } else if ($index === 'phone_numbers') {
            // 保证同一个 store_id 下 phone_number 唯一性
            $doc['id'] = $data['store_id'] . '_' . $data['phone_number'];
        }
        
        $doc['index'] = $index;
        
        return $this->client->index($doc);
    }
    
    /**
     * 根据条件查询数据
     *
     * @param array $conditions 查询条件
     *
     * @return Elasticsearch|Promise
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function search(array $conditions): Elasticsearch|Promise
    {
        $query = [];
        $index = $this->params['index'];
        // 构造查询条件
        foreach ($conditions as $field => $value) {
            if (is_string($value)) {
                // 字符串类型，使用正则匹配
                $query[] = [
                    'regexp' => [
                        $field => $value,
                    ],
                ];
            } else {
                // 数字类型，使用范围查询
                $query[] = [
                    'range' => [
                        $field => $value,
                    ],
                ];
            }
        }
        
        $params['body'] = [
            'query' => [
                'bool' => [
                    'must' => $query,
                ],
            ],
        ];
        
        return $this->client->search($params);
    }
    
    /**
     * 更新数据
     *
     * @param array $data 更新的数据
     *
     * @return Elasticsearch|Promise
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function update(array $data): Elasticsearch|Promise
    {
        $params = $this->params;
        $index  = $params['index'];
        if (empty($data['phone_number'])) throw new MissingParameterException('phone_number ');
        if ($index === 'virtual_phone_numbers') {
            // 保证 phone_number 唯一性
            $id = $data['phone_number'];
        } else if ($index === 'phone_numbers') {
            // 保证同一个 store_id 下 phone_number 唯一性
            $id = $data['store_id'] . '_' . $data['phone_number'];
        }
        /**
         * @var $id
         */
        $params['id']   = $id;
        $params['body'] = [
            'doc' => $data,
        ];
        
        return $this->client->update($params);
    }
}
