<?php

namespace app\logic\PhoneNumberTraits;

use app\exception\CustomException;
use app\library\EsSearch;
use app\logic\NumberBrandLogic;
use app\logic\PhoneNumberLogic;
use app\logic\PhoneNumberStoreLogic;
use app\logic\RegionLogic;
use app\model\Number;
use app\model\Operator;
use app\model\PhoneNumberStore;
use app\model\ProductFancyNumber;
use app\model\ProductNormalNumber;
use app\model\ProductPackageNumber;
use app\service\ExcelReader;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Support\Arr;
use OpenSpout\Common\Exception\IOException;
use RedisException;

trait ImportTrait
{
    
    protected string $importFile;
    protected array  $taskData = [
        'total'      => 0,
        'current'    => 0,
        'message'    => NULL,
        'status'     => 0,
        'error_data' => [],
    ];
    
    /**
     * @param $file
     * @param $store_id
     *
     * @return true
     * @throws CustomException
     * @throws IOException
     * @throws RedisException
     * @throws AuthenticationException
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function import($file, $store_id): bool
    {
        
        // todo: 导入时候 ,直接返回一个  es对象,  不要每次重新连接
        $this->importFile = storagePath("hidden/{$file}");
        
        // 判断类型
        $chunkSize   = 2000;
        $store       = PhoneNumberStoreLogic::getOne($store_id);
        $esIndexName = static::checkIndex($store['type'], TRUE);
        // 获取唯一值 key
        $esId = static::getEsIndexId($store['type']);
        
        $model = $store['type'] == PhoneNumberStore::TYPE_FANCY ? ProductFancyNumber::class : (PhoneNumberStore::TYPE_PACKAGE ? ProductPackageNumber::class : ProductNormalNumber::class);
        // 读取文件之前需要初始化一些变量，来存储各种映射和转换的关系。
        // $brandMap 用于存储品牌信息，$regionMap用于省市映射， $operators用于处理运营商信息。
        $brandMap  = NumberBrandLogic::getBrandsNameIdMap(TRUE);
        $regionMap = RegionLogic::getRegionNameCodeMap(TRUE);
        $operators = array_flip(Operator::OPERATORS); // 将运营商名称转换为对应的数字编码，并且反转数组键值对
        
        foreach ($operators as $key => $value) {
            $operators[$key]          = $value; // 添加键值对
            $operators['中国' . $key] = $value; // 添加另一种表述
        }
        
        
        $rowId   = 0; // 计数器
        $headers = []; // 用于存储Excel中的标题行数据，供后面处理数据使用
        
        // es 初始化
        $es = EsSearch::use($esIndexName);
        
        $start       = time();
        $excelReader = new ExcelReader($this->importFile);
        $total       = $excelReader->getLastRowIndex();

        $excelReader->start();
        
        $documents = [];
        
        // 遍历Excel文档，每个循环都是一行数据。如果不为空，则进行处理。
        foreach ($excelReader as $row) {
            
            if ($row !== NULL) {
                $data = $row->toArray(); // 取得数组

                // 如果是第一行，将标题存储在$headers中。
                if ($rowId == 0) {
                    $headers = PhoneNumberLogic::handleExcelHeader($data);
                } else { // 否则就是数据行，需要处理数据，然后存储到数据库和Elasticsearch索引中
                    // 提取公共的字段
                    $commonKeys = array_intersect_key($data, $headers);
                    // 标题与数据合并成一个新的数组
                    $newArray = array_combine(
                        array_values($headers), // 只保留标题数组的值
                        array_values($commonKeys) // 只保留内容数组的值
                    );
                    
                    // todo：处理数据
                    // 处理运营商和地区信息
                    if (empty($newArray['operator_name'])) {
                        continue;
                    }
                    $newArray['operator'] = $operators[trim(Arr::pull($newArray, 'operator_name'))] ?? 0;
                    if (empty($newArray['operator'])) {
                        continue;
                    }
                    
                    $newArray['province_code'] = $regionMap[0][trim($newArray['province'])] ?? NULL;
                    $newArray['city_code']     = $regionMap[1][trim($newArray['city'])] ?? NULL;
                    
                    if (empty($newArray['province_code']) || empty($newArray['city_code'])) {
                        continue;
                    }
                    
                    // 处理品牌信息
                    $brandPhoneNum = Arr::pull($newArray, "brand_phone_num") ?? NULL;
                    if (!empty($newArray['brand']) && empty($brandMap[trim($newArray['brand'])])) {
                        $brandId                      = NumberBrandLogic::create([
                            'name'      => $newArray['brand'],
                            'operator'  => $newArray['operator'],
                            'phone_num' => $brandPhoneNum,
                        ]);
                        $brandMap[$newArray['brand']] = $brandId;
                    }
                    // 将storeId 推入
                    $newArray['store_id'] = $store_id;
                    // es部分
                    $check = $es->where('number', $newArray['number']);
                    if ($store['type'] === PhoneNumberStore::TYPE_PACKAGE) {
                        $check->where('store_id', $store_id);
                    }
                    
                    
                    $exist = $check->first();
                    $es->reset();
                    
                    if (empty($exist)) {
                        $newArray['timestamp'] = microtime(TRUE);
                        $newArray['status']    = Number::STATUS_NORMAL;
                        $documents[]           = $newArray;
                    }
                    
                    if (count($documents) % $chunkSize === 0 && count($documents) > 0) {
                        $es->createBatch($documents, $esId);
                        $model::insert($documents);
                        $documents = [];
                        var_dump($rowId);
                    }
                }
            } else {
                var_dump('???');
            }
            $rowId ++;
        }
        
        if (count($documents)) {
            $es->createBatch($documents, $esId);
            $model::insert($documents);
        }
        var_dump(time() - $start);
        return TRUE;
    }
    
    /**
     * 处理Excel表头行，将表头字符串转换成对应的数组键名。
     *
     * @param array $headerRow 表头行数据
     *
     * @return array $headerArray 数组键名列表
     */
    public static function handleExcelHeader(array $headerRow): array
    {
        $headerArray = []; // 定义用于存储键名的数组
        foreach ($headerRow as $index => $string) { // 遍历表头行
            $string  = preg_replace("/[^\x{4e00}-\x{9fa5}]/u", "", $string); // 只保留中文字符
            $keyName = match (TRUE) { // 使用match语句判断条件
                str_contains($string, '省'), str_contains($string, '省份') => 'province',
                str_contains($string, '市'), str_contains($string, '归属地') => 'city',
                str_contains($string, '号码') => 'number',
                str_contains($string, '品牌') => 'brand',
                str_contains($string, '电话') => 'brand_phone_num',
                str_contains($string, '网络'), str_contains($string, '运营商') => 'operator_name',
                str_contains($string, '价格') => 'price',
                str_contains($string, '底价') => 'price_floor',
                str_contains($string, '资费'), str_contains($string, '套餐') => 'package',
                default => NULL, // 如果没有匹配到任何关键字，则将键名设置为null
            };
            
            if ($keyName !== NULL) {
                $headerArray[$index] = $keyName;
            }
        }
        return $headerArray; // 返回键名列表
    }
    
    public static function _toError()
    {
    
    }
}