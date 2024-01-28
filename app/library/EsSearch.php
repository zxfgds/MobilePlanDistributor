<?php

namespace app\library;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Elastic\Elasticsearch\Response\Elasticsearch;
use Exception;
use Http\Promise\Promise;

class EsSearch
{
    private Client  $client;
    private string  $index;
    protected array $body;
    protected array $source;
    
    /**
     * @throws AuthenticationException
     */
    public function __construct()
    {
        $this->client = ClientBuilder::create()->setHosts(['127.0.0.1:9200'])->build();
    }
    
    /**
     * 初始化方法，传入索引名称
     *
     * @param string $index
     *
     * @return EsSearch
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     * @throws AuthenticationException
     */
    public static function use(string $index): EsSearch
    {
        $esSearch        = new self();
        $esSearch->index = $index;
        if (!$esSearch->indexExists($index)) {
            throw new ServerResponseException("索引 {$index} 不存在，请先创建索引");
        }
        return $esSearch;
    }
    
    
    /**
     * 创建记录
     * 可传入单条数据，也可传入多条数据的数组
     *
     * @param array        $data
     * @param array|string $idColumns
     *
     * @return Elasticsearch|Promise
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function create(array $data, array|string $idColumns = 'id'): Elasticsearch|Promise
    {
        
        $params = [
            'index' => $this->index,
            'id'    => $this->getId($idColumns, $data),
            'body'  => $data,
        ];
        
        return $this->client->index($params);
    }
    
    
    /**
     * 批量写入文档
     *
     * @param array        $documents 包含文档的数组
     * @param array|string $idColumns
     *
     * @return Elasticsearch|Promise
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public
    function createBatch(array $documents, array|string $idColumns = 'id'): Elasticsearch|Promise
    {
        $params = ['body' => []];
        
        foreach ($documents as $document) {
            
            $id = $this->getId($idColumns, $document);
            
            // 添加操作元数据
            $params['body'][] = [
                'index' => [
                    '_index' => $this->index,
                    '_id'    => $id, // 如果您有自定义的文档 ID，请在这里设置
                ],
            ];
            
            // 添加文档源数据
            $params['body'][] = $document;
        }
        
        // 执行批量操作
        return $this->client->bulk($params);
    }
    
    protected function getId($idColumns, $data)
    {
        if (is_array($idColumns)) {
            $idArray = [];
            foreach ($idColumns as $column) {
                if (empty($data[$column])) continue;
                $idArray[] = $data[$column];
            }
            $id = implode('_', $idArray);
        } else {
            $id = $data[$idColumns];
        }
        return $id;
    }
    
    /**
     * upsert方法
     * 根据传入条件，如果存在则更新，如果不存在则新增
     *
     * @param array $condition
     * @param array $data
     *
     * @return Elasticsearch|Promise
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public
    function upsert(array $condition, array $data): Elasticsearch|Promise
    {
        $params = [
            'index' => $this->index,
            'id'    => $data['id'] ?? NULL,
            'body'  => [
                'script' => [
                    'source' => $this->buildUpdateScript($data),
                    'lang'   => 'painless',
                    'params' => $data,
                ],
                'upsert' => $data,
            ],
        ];
        
        return $this->client->update($params);
    }
    
    /**
     * where 查询条件
     *
     * @param string       $field
     * @param string|array $condition
     *
     * @return $this
     */
    public function where(string $field, string|array $condition): EsSearch
    {
        if (is_string($condition)) {
            // 完全相等
            $this->body['query']['bool']['filter'][] = [
                'query_string' => [
                    'default_field' => $field,
                    'query'         => $condition,
                ],
            ];
        } else {
            foreach ($condition as $key => $value) {
                switch ($key) {
                    case 'fuzzy': // 模糊搜索（整个字段匹配）
                        $this->body['query']['bool']['filter'][] = [
                            'wildcard' => [
                                $field => '*' . $value . '*',
                            ],
                        ];
                        break;
                    case 'fuzzy_end': // 模糊搜索（尾部匹配）
                        $this->body['query']['bool']['filter'][] = [
                            'wildcard' => [
                                $field => '*' . $value,
                            ],
                        ];
                        break;
                    case 'compare': // 大小比较
                        $this->body['query']['bool']['filter'][] = [
                            'range' => [
                                $field => [$value[0] => $value[1]],
                            ],
                        ];
                        break;
                    case 'is_null': // 为空搜索
                        $this->body['query']['bool']['filter'][] = [
                            'bool' => [
                                'must_not' => [
                                    'exists' => [
                                        'field' => $field,
                                    ],
                                ],
                            ],
                        ];
                        break;
                    case 'regexp': // 正则搜索
                        $this->body['query']['bool']['filter'][] = [
                            'regexp' => [
                                $field => $value,
                            ],
                        ];
                        break;
                    case 'fixed_regexp': // 定量正则搜索
                        $this->body['query']['bool']['filter'][] = [
                            'regexp' => [
                                $field => '^' . $value . '$',
                            ],
                        ];
                        break;
                    case 'position': // 指定位置搜索
                        $searchPattern = '';
                        foreach ($value as $position => $number) {
                            $searchPattern .= '(?=.{' . $position . '}' . $number . ')';
                        }
                        $this->body['query']['bool']['filter'][] = [
                            'regexp' => [
                                $field => $searchPattern . '.*',
                            ],
                        ];
                        break;
                }
            }
        }
        return $this;
    }
    
    /**
     * 正则查询
     *
     * @param string       $key
     * @param string|array $conditions
     *
     * @return $this
     */
    public function whereReg(string $key, string|array $conditions): EsSearch
    {
        if (is_array($conditions)) {
            $regexQueries = [];
            foreach ($conditions as $condition) {
                $regexQueries[] = ['regexp' => [$key => $condition]];
            }
            $this->body['query']['bool']['filter'][] = ['bool' => ['should' => $regexQueries]];
        } else {
            $this->body['query']['bool']['filter'][] = ['regexp' => [$key => $conditions]];
        }
        return $this;
    }
    
    /**
     * OR 查询条件
     *
     * @param array|string $params
     * @param string|null  $operator
     * @param mixed|null   $value
     *
     * @return $this
     */
    public
    function orWhere(array|string $params, string|null $operator = NULL, mixed $value = NULL): EsSearch
    {
        if (is_array($params)) {
            $shouldQueries = [];
            foreach ($params as $key => $val) {
                $shouldQueries[] = ['term' => [$key => $val]];
            }
            $this->body['query']['bool']['filter'][] = ['bool' => ['should' => $shouldQueries]];
        } else {
//                $this->body['query']['bool']['filter'][] = ['bool' => ['should' => [['term' => [$params => $operator ?? $value]]]]];
            if (is_null($operator)) {
                $this->body['query']['bool']['filter'][] = ['bool' => ['should' => [['term' => [$params => $value]]]]];
            } else {
                $this->body['query']['bool']['filter'][] = ['bool' => ['should' => [['term' => [$params => $operator]]]]];
            }
        }
        
        return $this;
    }
    
    /**
     * 查询字段值在给定数组中的记录
     *
     * @param string $key
     * @param array  $values
     *
     * @return $this
     */
    public
    function whereIn(string $key, array $values): EsSearch
    {
        $this->body['query']['bool']['filter'][] = ['terms' => [$key => $values]];
        return $this;
    }
    
    /**
     * 查询字段值不在给定数组中的记录
     *
     * @param string $key
     * @param array  $values
     *
     * @return $this
     */
    public
    function whereNotIn(string $key, array $values): EsSearch
    {
        $this->body['query']['bool']['filter'][] = ['bool' => ['must_not' => [['terms' => [$key => $values]]]]];
        return $this;
    }
    
    /**
     * 查询字段值在给定范围内的记录
     *
     * @param string $key
     * @param array  $range
     *
     * @return $this
     */
    public
    function whereBetween(string $key, array $range): EsSearch
    {
        $this->body['query']['bool']['filter'][] = ['range' => [$key => ['gte' => $range[0], 'lte' => $range[1]]]];
        return $this;
    }
    
    /**
     * 查询字段值不在给定范围内的记录
     *
     * @param string $key
     * @param array  $range
     *
     * @return $this
     */
    public
    function whereNotBetween(string $key, array $range): EsSearch
    {
        $this->body['query']['bool']['filter'][] = [
            'bool' => [
                'must_not' => [
                    ['range' => [$key => ['gte' => $range[0], 'lte' => $range[1]]]],
                ],
            ],
        ];
        return $this;
    }
    
    /**
     * limit 查询限制条数
     *
     * @param int $number
     *
     * @return $this
     */
    public
    function limit(int $number): EsSearch
    {
        $this->body['size'] = $number;
        return $this;
    }
    
    /**
     * page 查询分页
     *
     * @param int $page
     *
     * @return $this
     */
    public
    function page(int $page): EsSearch
    {
        $this->body['from'] = ($page - 1) * ($this->body['size'] ?? 10);
        return $this;
    }
    
    
    /**
     * select 查询指定字段
     *
     * @param string ...$fields
     *
     * @return $this
     */
    public
    function select(string ...$fields): EsSearch
    {
        $this->source = $fields;
        return $this;
    }
    
    /**
     * 获取查询结果
     *
     * @return array
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public
    function get(): array
    {
        $params = [
            'index' => $this->index,
            'body'  => $this->body,
        ];
        
        if (!empty($this->source)) {
            $params['_source'] = $this->source;
        }
        
        $response = $this->client->search($params);
        
        $data = [];
        if ($response['hits']['hits']) {
            foreach ($response['hits']['hits'] as $doc) {
                $data[] = $doc['_source'];
            }
        }
        
        return $data;
        
    }
    
    /**
     * 排序
     *
     * @param string $sortBy
     * @param string $sortName
     *
     * @return $this
     */
    public
    function orderBy(string $sortBy, string $sortName): EsSearch
    {
        $this->body['sort'] = [$sortBy => $sortName];
        return $this;
    }
    
    /**
     * 获取符合条件的总数
     *
     * @return int
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public
    function count(): int
    {
//        $this->body['size'] = 0;
        $params = [
            'index' => $this->index,
            'body'  => $this->body,
        ];
        if (!empty($this->source)) {
            $params['_source'] = $this->source;
        }
        $response = $this->client->search($params);
        
        return $response['hits']['total']['value'] ?? 0;
    }
    
    /**
     * 分页获取数据
     *
     * @param int $page
     * @param int $pageSize
     *
     * @return array
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public
    function paginate(int $page = 1, int $pageSize = 20): array
    {
        $this->page($page);
        $this->limit($pageSize);
        $total = $this->count();
        $list  = $this->get();
        
        
        return [
            'pageTotal' => ceil($total / $pageSize),
            'page'      => $page,
            'pageSize'  => $pageSize,
            'total'     => $total,
            'list'      => $list,
        ];
    }
    
    /**
     * 获取第一条记录
     *
     * @return array|null
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public
    function first(): ?array
    {
        $this->body['size'] = 1;
        $params             = [
            'index' => $this->index,
            'body'  => $this->body,
        ];
        
        if (!empty($this->source)) {
            $params['_source'] = $this->source;
        }
        $response = $this->client->search($params);
        return $response['hits']['hits'][0]['_source'] ?? NULL;
    }
    
    /**
     * 删除操作
     *
     * @return Elasticsearch|Promise
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public
    function delete(): Elasticsearch|Promise
    {
        $params = [
            'index' => $this->index,
            'body'  => $this->body,
        ];
        
        return $this->client->deleteByQuery($params);
    }
    
    
    /**
     * 重置查询条件
     *
     * @return void
     */
    public
    function reset(): void
    {
        $this->body = [];
    }
    
    /**
     * 更新操作
     *
     * @param array $data
     *
     * @return Elasticsearch|Promise
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public
    function update(array $data): Elasticsearch|Promise
    {
        $params = [
            'index' => $this->index,
            'id'    => $data['id'],
            'body'  => [
                'doc' => $data,
            ],
        ];
        
        return $this->client->update($params);
    }
    
    
    /**
     * 清除索引
     *
     * @return Elasticsearch|Promise
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function clear(): Elasticsearch|Promise
    {
        $params = [
            'index' => $this->index,
        ];
        
        return $this->client->indices()->delete($params);
    }
    
    /**
     * 构建更新脚本
     *
     * @param array $data
     *
     * @return string
     */
    protected
    function buildUpdateScript(array $data): string
    {
        $script = [];
        foreach ($data as $key => $value) {
            $script[] = "ctx._source.$key = params.$key";
        }
        return implode('; ', $script);
    }
    
    
    /**
     * 检查索引是否存在
     *
     * @param string $indexName
     *
     * @return bool
     * @throws AuthenticationException
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public
    static function indexExists(string $indexName): bool
    {
        $client = ClientBuilder::create()->setHosts(['127.0.0.1:9200'])->build();
        $params = ['index' => $indexName];
        return $client->indices()->exists($params)->asBool();
    }
    
    /**
     * 创建索引
     *
     * @param string $indexName
     * @param array  $fieldMappings
     * @param array  $aliases
     *
     * @return Elasticsearch|Promise
     * @throws Exception
     */
    public
    static function createIndex(string $indexName, array $fieldMappings, array $aliases = []): Elasticsearch|Promise
    {
        // 创建Elasticsearch客户端实例
        $client = ClientBuilder::create()->setHosts(['127.0.0.1:9200'])->build();
        
        // 检查索引是否已经存在
        if (static::indexExists($indexName)) {
            throw new Exception("索引 {$indexName} 已经存在。");
        }
        
        // 构建字段映射
        $properties = [];
        foreach ($fieldMappings as $field => $mapping) {
            $properties[$field] = $mapping;
        }
        
        // 创建索引参数
        $params = [
            'index' => $indexName,
            'body'  => [
                'settings' => [
                    'number_of_shards'   => 1,
                    'number_of_replicas' => 1,
                ],
                'mappings' => [
                    'properties' => $properties,
                ],
            ],
        ];
        
        // 如果有别名，将其添加到参数中
        if (!empty($aliases)) {
            $params['body']['aliases'] = array_flip($aliases);
        }
        
        // 创建索引
        $response = $client->indices()->create($params);
        
        // 检查响应以确定索引是否成功创建
        if ($response['acknowledged'] ?? FALSE) {
            return $response;
        } else {
            throw new Exception("创建索引 {$indexName} 失败。");
        }
    }
    
    
    /**
     * 删除索引
     * @return bool
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public
    function deleteIndex(): bool
    {
        $params = ['index' => $this->index];
        return $this->client->indices()->delete($params)['acknowledged'];
    }
    
    
}

