<?php

namespace app\library;

use app\service\AmapService;
use Exception;

class AddressParser
{
    protected static array $divisionsData;
    
    
    /**
     * 加载行政区划分 JSON 数据文件
     *
     * @param string|null $jsonFilePath JSON 文件路径，默认值为 storagePath('region_flatten.json')
     */
    public static function loadDivisionsData(string $jsonFilePath = NULL): void
    {
        if ($jsonFilePath === NULL) {
            $jsonFilePath = storagePath('region_flatten.json');
        }
        $jsonContent         = file_get_contents($jsonFilePath);
        self::$divisionsData = json_decode($jsonContent, TRUE);
    }
    
    
    /**
     * 解析地址字符串并返回数组格式的地址信息
     *
     * @param string $address 待解析的地址字符串
     *
     * @return array 包含省、市、区/县、街道和详细地址的数组
     * @throws Exception
     */
    public static function parseAddress(string $address): array
    {
        if (!self::$divisionsData) {
            throw new Exception("Divisions data not loaded.");
        }
        
        $result = [
            'province' => '',
            'city'     => '',
            'district' => '',
        ];
        // 遍历省、市和区/县数据
        // 遍历省、市和区/县数据
        $province = FALSE;
        $city     = FALSE;
        $district = FALSE;
        foreach (self::$divisionsData as $divisionType => $divisions) {
            // todo 打错特错  从第56行就决定了 结果会错.
            foreach ($divisions as $code => $division) {
                // 如果地址字符串包含当前行政区划分的名称
                if (str_contains($address, $division['name'])) {
                    // 将匹配到的行政区划分名称添加到结果数组中，并从原始地址字符串中移除
                    if ($divisionType === 'provinces' && !$province) {
                        var_dump("province");
                        $result['province'] = $division['name'];
                        $province           = TRUE;
                    } else if ($divisionType === 'cities' && !$city) {
                        var_dump("CITY");
                        $result['city'] = $division['name'];
                        $city           = TRUE;
                    } else if ($divisionType === 'districts' && !$district) {
                        $result['district'] = $division['name'];
                        $district           = TRUE;
                    }
                    // 替换第一个匹配到的字符串
                    $address = mb_substr($address, mb_strlen($division['name']));
                }
            }
        }
        // 如果未找到省、市或区/县信息，则调用 AmapService 的 addressToGeoCode 方法
//        if (empty($result['province']) || empty($result['city']) || empty($result['district'])) {
//            var_dump("API");
//            //todo 接口
//        }
        
        // 将剩余的地址字符串作为街道和详细地址
        $result['street'] = trim($address);
        
        return $result;
    }
}