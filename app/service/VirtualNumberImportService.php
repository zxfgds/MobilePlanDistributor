<?php

namespace app\service;

use app\library\Arr;
use app\logic\NumberBrandLogic;
use app\logic\ProductFancyNumberLogic;
use app\logic\RegionLogic;
use app\model\NumberBrand;
use app\model\Operator;
use app\model\ProductFancyNumber;
use Exception;
use OpenSpout\Reader\XLSX\Reader;

class VirtualNumberImportService
{
    
    private array $headers = [];
    
    protected array $brands = [];
    
    protected array $regions = [];
    
    protected array $errors = [];
    
    public function __construct()
    {
        // 初始化
    }
    
    
    public function read($filePath)
    {
        $start = getMicrometer();
        
        // 初始化 城市
        // todo 区域有修改
        $regions       = RegionLogic::getFullRegion();
        $this->brands  = NumberBrand::pluck('id', 'name')->toArray();
        $this->regions = $regions;
        
        try {
            $reader = new Reader();
            $reader->open($filePath);
            $sheet       = $reader->getSheetIterator()->current();
            $rowIterator = $sheet->getRowIterator();
            $rowIterator->rewind();
            $titleRow = $rowIterator->current()->toArray();
            
            $headers = ProductFancyNumberLogic::handleExcelHeader($titleRow);
//            $rowIterator->next();
            $x = 0;
            
            $data = [];
            
            foreach ($rowIterator as $row) {
                $rowData = $row->toArray();
                $array   = [];
                
                foreach ($headers as $key => $value) {
                    $array[$value] = $rowData[$key];
                }
                
                if (!is_numeric($array['number'])) continue;
                try {
                    
                    
                    $data[] = $this->handleRow($array);


//                    $insert = ProductFancyNumber::insertOrIgnore($array);
//                    if (!$insert) $this->error($array['number'], '已存在');
//                    if ($x % 2000 === 0) var_dump(formatMemorySize());
                    $x ++;
                    if (count($data) >= 2000) {
                        var_dump(formatMemorySize());
                        var_dump(count($data));
                        $insert = ProductFancyNumber::insertOrIgnore($data);
                        $data   = [];
                        var_dump($insert);
                        var_dump(formatMemorySize());
                        
                    }
                    
                } catch (Exception $e) {
                    $this->error($array['number'], $e->getMessage());
                    continue;
                }
                
                unset($array); // 释放 rowData 内存
                unset($rowData); // 释放 rowData 内存
            }
            
            if (count($data)) {
                ProductFancyNumber::insertOrIgnore($data);
                $data = [];
            }
        } catch (Exception $e) {
            var_dump($e);
            $this->error($array['number'], $e->getMessage());
        }
    }
    
    
    /**
     * @throws Exception
     */
    protected function handleRow($array)
    {
        // 补充归属地
        // 补充品牌
        $province = $this->regions[$array['province']];
        
        if (empty($province)) throw new Exception('系统中找不到该省份:' . $province);
        
        try {
            $city = $province['cities'][$array['city']];
        } catch (Exception $e) {
            throw new Exception('系统中找不到:' . $array['city']);
        }
        
        $array['province_code'] = $province['code'];
        $array['city_code']     = $city['code'];
        // brand
        try {
            
            if (empty($this->brands[$array['brand']])) {
                $newBrand                      = ['name' => $array['brand'], 'phone_num' => Arr::pull($array, 'brand_phone_num')];
                $brandId                       = NumberBrandLogic::create($newBrand);
                $this->brands[$array['brand']] = $brandId;
                
            }
            $array['brand'] = $this->brands[$array['brand']];
            unset($array['brand_phone_num']);
        } catch (\Exception $e) {
            var_dump($e);
            exit;
        }
        
        // operator
        
        try {
            $operators         = array_flip(Operator::OPERATORS);
            $array['operator'] = $operators[Arr::pull($array, 'operator_name')];
        } catch (\Exception $e) {
            throw new  Exception('运营商错误:' . $array['operator_name']);
        }
        
        return $array;
    }
    
    protected function error($number, $msg = NULL)
    {
        $this->errors[] = ['number' => $number, 'msg' => $msg];
    }
    
    public function write()
    {
    
    }
}