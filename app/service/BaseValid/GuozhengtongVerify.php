<?php

namespace app\service\BaseValid;

use Exception;

class GuozhengtongVerify
{
    private string $apiKey;
    
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }
    
    /**
     * 实名校验
     *
     * @param string $realName 真实姓名
     * @param string $idNumber 身份证号
     *
     * @return array
     *
     * @throws Exception
     */
    public function verify(string $realName, string $idNumber): array
    {
        $url = 'https://api.realname.com/verify?realName=' . $realName . '&idNumber=' . $idNumber . '&apiKey=' . $this->apiKey;
        $result = $this->curl($url);
        $result = json_decode($result, true);
        if ($result['status'] == '1') {
            return $result['data'];
        }
        throw new Exception($result['message']);
    }
    
    /**
     * 手机号校验
     *
     * @param string $phoneNumber 手机号
     *
     * @return array
     *
     * @throws Exception
     */
    public function verifyPhone(string $phoneNumber): array
    {
        $url = 'https://api.realname.com/verifyPhone?phoneNumber=' . $phoneNumber . '&apiKey=' . $this->apiKey;
        $result = $this->curl($url);
        $result = json_decode($result, true);
        if ($result['status'] == '1') {
            return $result['data'];
        }
        throw new Exception($result['message']);
    }
    
    /**
     * curl请求
     *
     * @param string $url
     *
     * @return string
     */
    private function curl(string $url): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}
