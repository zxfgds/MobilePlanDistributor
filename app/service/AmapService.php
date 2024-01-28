<?php

namespace app\service;

class AmapService
{
    private string $apiKey;
    
    public function __construct(string $apiKey = NULL)
    {
        $this->apiKey = $apiKey ?? "cf1ed665c81c6b21f146f439375ca93d";
    }
    
    /**
     *
     * 根据 IP 地址解析地理位置
     *
     * @param string $ip IP 地址
     *
     * @return array 地理位置数组，包含省份、城市、地区编码、矩形区域等信息
     */
    public function ipToAddress(string $ip): array
    {
        // 构造请求 URL
        $url = 'https://restapi.amap.com/v3/ip?ip=' . $ip . '&key=' . $this->apiKey;
        // 发起请求并获取结果
        $result = $this->curl($url);
        $result = json_decode($result, TRUE);
        // 判断请求是否成功
        if ($result['status'] == '1') {
            // 获取省份、城市、地区编码、矩形区域等信息
            $province  = $result['province'];
            $city      = $result['city'];
            $adcode    = $result['adcode'];
            $rectangle = $result['rectangle'];
        }
        return [];
    }
    
    /**
     * @param string $address
     *
     * @return array
     */
    public function addressToGeoCode(string $address): array
    {
        $url    = 'https://restapi.amap.com/v3/geocode/geo?address=' . $address . '&key=' . $this->apiKey;
        $result = $this->curl($url);
        $result = json_decode($result, TRUE);
        if ($result['status'] == '1' && !empty($result['geocodes'])) {
            $province = $result['geocodes'][0]['province'];
            $city     = $result['geocodes'][0]['city'];
            $district = $result['geocodes'][0]['district'];
            $street   = $result['geocodes'][0]['street'];
            $number   = $result['geocodes'][0]['number'];
            return [
                'province' => $province,
                'city'     => $city,
                'district' => $district,
                'address'  => $street,
            ];
        }
        return [];
    }
    
    private function curl(string $url): bool|string
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}
