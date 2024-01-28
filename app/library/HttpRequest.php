<?php

namespace app\library;

use Exception;

class HttpRequest
{
    private int   $concurrency = 10;
    private int   $sleep       = 0;
    private array $header      = [
        'Content-Type' => 'application/json',
    ];
    
    /**
     * 设置请求并发数
     *
     * @param int $concurrency 并发数
     *
     * @return $this
     */
    public function setConcurrency(int $concurrency): static
    {
        $this->concurrency = $concurrency;
        return $this;
    }
    
    /**
     * 设置请求间隔时间
     *
     * @param int $sleep 休眠时间（单位：秒）
     *
     * @return $this
     */
    public function setSleep(int $sleep): static
    {
        $this->sleep = $sleep;
        return $this;
    }
    
    /**
     * 设置请求头
     *
     * @param array $header 请求头
     *
     * @return $this
     */
    public function setHeader(array $header): static
    {
        $this->header = array_merge($this->header, $header);
        return $this;
    }
    
    /**
     * 单独请求
     *
     * @param string       $method 请求方法
     * @param string       $url    请求地址
     * @param array|string $data   请求参数或数据
     *
     * @return mixed
     * @throws Exception
     */
    public function request(string $method, string $url, array|string $data = []): mixed
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        if (!empty($this->header)) {
            $header = [];
            foreach ($this->header as $key => $value) {
                $header[] = $key . ': ' . $value;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        
        if ($method === 'POST' || $method === 'PUT') {
            if (is_array($data)) {
                $data = json_encode($data);
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }
        
        curl_close($ch);
        return json_decode($response, TRUE);
    }
    
    /**
     * 并发请求
     *
     * @param array $requests 请求数组，每个请求包含方法、地址、数据
     *
     * @return array
     * @throws Exception
     */
    public function concurrentRequest(array $requests): array
    {
        $concurrency = min(count($requests), $this->concurrency);
        $mh          = curl_multi_init();
        $chs         = [];
        $results     = [];
        $running     = 0;
        $index       = 0;
        while ($running > 0 || $index < count($requests)) {
            while ($running < $concurrency && $index < count($requests)) {
                $request = $requests[$index];
                $ch      = curl_init();
                curl_setopt($ch, CURLOPT_URL, $request['url']);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request['method']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                
                if (!empty($this->header)) {
                    $header = [];
                    foreach ($this->header as $key => $value) {
                        $header[] = $key . ': ' . $value;
                    }
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                }
                
                if ($request['method'] === 'POST' || $request['method'] === 'PUT') {
                    if (is_array($request['data'])) {
                        $request['data'] = json_encode($request['data']);
                    }
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $request['data']);
                }
                
                curl_multi_add_handle($mh, $ch);
                $chs[$index] = $ch;
                $index ++;
                $running ++;
            }
            
            $active = NULL;
            do {
                $mrc = curl_multi_exec($mh, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            
            while ($active && $mrc == CURLM_OK) {
                if (curl_multi_select($mh) == - 1) {
                    usleep(100);
                }
                
                do {
                    $mrc = curl_multi_exec($mh, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
            
            foreach ($chs as $key => $ch) {
                if (curl_error($ch)) {
                    throw new Exception(curl_error($ch));
                }
                $results[$key] = json_decode(curl_multi_getcontent($ch), TRUE);
                curl_multi_remove_handle($mh, $ch);
                curl_close($ch);
                $running --;
            }
            
            if ($this->sleep > 0) {
                sleep($this->sleep);
            }
        }
        
        curl_multi_close($mh);
        return $results;
    }
}
