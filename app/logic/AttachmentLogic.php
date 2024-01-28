<?php

namespace app\logic;

use app\model\Attachment;
use app\service\Storage\StorageService;
use RedisException;
use support\Log;

class AttachmentLogic extends BaseLogic
{
    protected static string $model = Attachment::class;
    
    /**
     * 上传文件并存储信息到数据库中
     *
     * @param string $filepath 上传的文件路径
     * @param bool   $isPublic 是否为公共文件，默认为 TRUE
     *
     * @return bool|array 成功上传并存储信息时，返回包含文件 URL 的数组；否则返回 FALSE
     * @throws RedisException
     */
    public static function upload(string $filepath, bool $isPublic = TRUE): bool|array
    {
        // 获取存储方式
        $storage = SettingLogic::get('storage', 'default') ?? 'local';
        // 如果不是公共文件，强制使用本地存储
        $storage = !$isPublic ? 'local' : $storage;
        // 获取存储设置
        $settings = SettingLogic::get('storage', $storage) ?? [];
        
        try {
            // 调用 StorageService 上传文件
            $result  = (new StorageService($storage, $settings))->upload($filepath, $isPublic);
            
            $baseUrl = $settings['url'] ?? str_replace(['http://', 'https://'], '', env("APP_URL"));
            // 入库
            $data = [
                'filename'  => $result['filename'],
                'url'       => "https://" . $baseUrl . '/' . path_combine($result['path'], $result['filename']),
                'path'      => $result['path'],
                'size'      => filesize($filepath),
                'is_hidden' => !$isPublic,
                'location'  => !$isPublic ? 'hidden' : ($storage == 'local' ? 'public' : 'cloud'),
            ];
            // 将文件信息存储到数据库中
            static::create($data);
            // 返回包含文件 URL 的数组
            return ['url' => $data['url'], 'name' => $result['filename'], 'size' => $data['size'], 'path' => $data['path'], 'status' => 'success'];
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            // 记录错误日志并返回 FALSE
            Log::error($e);
            return FALSE;
        }
    }
    
}