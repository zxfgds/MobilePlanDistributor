<?php

namespace app\library;

use Redis;
use RedisException;

class RedisCache
{
    protected static $redis;
    
    /**
     * 获取数据
     *
     * @param $key
     * @param $default
     *
     * @return mixed|Redis|string|null
     * @throws RedisException
     */
    public static function get($key, $default = NULL): mixed
    {
        $fullKey = env('REDIS_PREFIX') . ':' . $key;
        $value   = self::getRedis()->get($fullKey);
        if ($value === FALSE) {
            return $default;
        }
        
        if (self::getRedis()->exists($fullKey . ':only_once')) {
            self::forget($key);
        }
        
        return jsonDecode($value);
    }
    
    /**
     * @param $key
     *
     * @return false|mixed|Redis|string
     * @throws RedisException
     */
    public static function check($key): mixed
    {
        $fullKey = env('REDIS_PREFIX') . ':' . $key;
        return self::getRedis()->get($fullKey);
    }
    
    /**
     * 存入数据
     *
     * @param     $key
     * @param     $value
     * @param int $minutes
     *
     * @return bool|Redis
     * @throws RedisException
     */
    public static function put($key, $value, int $minutes = 14400): bool|Redis
    {
        $value   = toJson($value);
        $fullKey = env('REDIS_PREFIX') . ':' . $key;
        if (is_null($minutes)) {
            return self::getRedis()->set($fullKey, $value);
        }
        
        return self::getRedis()->setex($fullKey, $minutes * 60, $value);
    }
    
    /**
     * 存入一次性数据
     *
     * @param     $key
     * @param     $value
     * @param int $minutes
     *
     * @return void
     * @throws RedisException
     */
    public static function flush($key, $value, int $minutes = 14400): void
    {
        $value   = toJson($value);
        $fullKey = env('REDIS_PREFIX') . ':' . $key;
        self::getRedis()->setex($fullKey, $minutes * 60, $value);
        self::getRedis()->set($fullKey . ':only_once', TRUE);
    }
    
    /**
     * 删除数据
     *
     * @param $key
     *
     * @return void
     * @throws RedisException
     */
    public static function forget($key): void
    {
        $fullKey = env('REDIS_PREFIX') . ':' . $key;
        self::getRedis()->del($fullKey);
        self::getRedis()->del($fullKey . ':only_once');
    }
    
    /**
     * 永久保存数据
     *
     * @param $key
     * @param $value
     *
     * @return void
     * @throws RedisException
     */
    public static function forever($key, $value): void
    {
        $value   = toJson($value);
        $fullKey = env('REDIS_PREFIX') . ':' . $key;
        self::getRedis()->set($fullKey, $value);
    }
    
    /**
     * 取出并删除数据
     *
     * @param      $key
     * @param null $default
     *
     * @return mixed|Redis|string|null
     * @throws RedisException
     */
    public static function pull($key, $default = NULL)
    {
        $value = self::get($key, $default);
        self::forget($key);
        
        return $value;
    }
    
    /**
     * @return Redis
     * @throws RedisException
     */
    protected static function getRedis(): Redis
    {
        if (!self::$redis) {
            $redis = new Redis();
            $redis->connect(config('redis.cache.host'), config('redis.cache.port'), config('redis.cache.database'));
            $redis->auth(config('redis.cache.password'));
            $redis->select(config('redis.cache.database'));
            self::$redis = $redis;
        }
        
        return self::$redis;
    }
}
