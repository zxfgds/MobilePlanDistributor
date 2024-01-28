<?php

namespace app\library;

class IpToAddress
{
    private string $apiKey;
    
    /**
     * IpAddressConverter constructor.
     *
     * @param string $apiKey 高德开发者平台API Key
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }
    
    /**
     * 将IP地址转换为地址
     *
     * @param string $ip IP地址
     *
     * @return array
     */
    public function convert(string $ip): array
    {
        $url    = 'https://restapi.amap.com/v3/ip?ip=' . $ip . '&output=json&key=' . $this->apiKey;
        $result = file_get_contents($url);
        $result = json_decode($result, TRUE);
        return [
            'province' => $result['province'],
            'city'     => $result['city'],
            'district' => $result['district'],
            'street'   => $result['street'],
        ];
    }
}
