<?php

namespace app\logic;

use app\library\RedisCache;
use app\model\Setting;
use Exception;
use RedisException;
use support\Db;
use support\Log;

class SettingLogic extends BaseLogic
{
    protected static string $model = Setting::class;
    protected static bool $useCache = TRUE;

    /**
     * 获取
     *
     * @param      $category
     * @param null $key
     * @param bool $retry
     *
     * @return mixed
     * @throws RedisException
     */
    public static function get($category, $key = NULL, bool $retry = FALSE): mixed
    {
        $cache = RedisCache::get(static::$model . "_SETTINGS");
        // 如果 $name 不为空，读取指定配置；否则读取 $category 下的所有配置
        $settings = $key ? ($cache[$category][$key] ?? NULL) : ($cache[$category] ?? NULL);

        // 如果读取到了配置，则返回该配置
        if ($settings) {
            return $settings;
        }

        static::makeCache();

        return $retry ? ($key ? NULL : []) : static::get($category, $key, TRUE);
    }


    /**
     * 保存
     *
     * @param $category
     * @param $key
     * @param $value
     *
     * @return void
     * @throws Exception
     */
    public static function set($category, $key, $value): void
    {
        if (empty($value) && $value !== 0) $value = NULL;
        Db::beginTransaction();
        try {
            Setting::updateOrCreate([
                'category' => $category,
                'key' => $key,
            ], [
                'category' => $category,
                'key' => $key,
                'value' => toJson($value),
            ]);
            Db::commit();
            static::makeCache();
        } catch (\Exception $e) {
            Log::error($e);
            Db::rollBack();
            throw new Exception('保存失败');
        }
    }

    /**
     * 生成缓存
     * @return void
     * @throws RedisException
     */
    public static function makeCache(): void
    {
        RedisCache::forget(static::$model . "_SETTINGS");
        $model = new static::$model();
        $settingData = $model->all()->toArray();
        if (empty($settingData)) return;
        $array = [];
        foreach ($settingData as $setting) {
            $array[$setting['category']][$setting['key']] = jsonDecode($setting['value']);
        }
        RedisCache::forever(static::$model . "_SETTINGS", $array);
    }

    public static function default($category, $key): ?array
    {
        $default = [
            'storage' => [
                'local' => ['url'],
                'qiniu' => ['url', 'accessKey', 'secretKey', 'bucket'],
                'tencent' => ['region', 'appId', 'secretId', 'secretKey'],
                'aliyun' => ['bucket', 'accessKeyId', 'accessKeySecret', 'endpoint'],
            ],
        ];

        return $default[$category][$key] ?? NULL;
    }
}