<?php

namespace app\service\Storage\Providers;

use OSS\Core\OssException;
use OSS\OssClient;
use support\Log;

class AliyunCloud extends Serve
{
    private OssClient $client;
    
    public function __construct()
    {
        parent::__construct();
        $this->client = NULL;
    }
    
    /**
     * 获取 OssClient 对象
     *
     * @return OssClient 返回 OssClient 对象
     */
    private function getClient(): OssClient
    {
        $accessKeyId     = $this->settings['accessKeyId'];
        $accessKeySecret = $this->settings['accessKeySecret'];
        $endpoint        = $this->settings['endpoint'];
        $this->client    = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
        return $this->client;
    }
    
    /**
     * 上传文件到阿里云 OSS
     *
     * @param string $filepath 文件路径
     * @param bool   $isPublic 是否公开访问，默认为 true
     *
     * @return array|bool 如果上传成功，返回文件信息数组；如果上传失败，则返回 false
     */
    public function upload(string $filepath, bool $isPublic = TRUE): array|bool
    {
        try {
            $bucket  = $this->settings['bucket'];
            $object  = basename($filepath);
            $options = [];
            if ($isPublic) {
                $options['x-oss-object-acl'] = 'public-read';
            }
            $this->getClient()->uploadFile($bucket, $object, $filepath, $options);
            $url = $this->getClient()->signUrl($bucket, $object, 3600); // 获取文件 URL
            return [
                'path'     => $this->newPath,
                'filename' => $this->newFileName,
                'url'      => $url,
            ];
        } catch (\Exception $e) {
            Log::error($e);
            return FALSE;
        }
    }
    
    /**
     * 删除阿里云 OSS 中的文件
     *
     * @param string $filename 文件名
     *
     * @return bool 如果删除成功，返回 true；如果删除失败，则返回 false
     */
    public function delete(string $filename): bool
    {
        try {
            $bucket = $this->settings['bucket'];
            $this->getClient()->deleteObject($bucket, $filename);
            return TRUE;
        } catch (\Exception $e) {
            Log::error($e);
            return FALSE;
        }
    }
    
    /**
     * 获取阿里云 OSS 中指定目录下的文件列表
     *
     * @param string $prefix  目录名
     * @param string $marker  标记符
     * @param int    $maxKeys 最大返回数量
     *
     * @return array 返回文件列表数组
     * @throws OssException
     */
    public function listFiles(string $prefix = '', string $marker = '', int $maxKeys = 100): array
    {
        $bucket  = $this->settings['bucket'];
        $options = [
            'prefix'   => $prefix,
            'marker'   => $marker,
            'max-keys' => $maxKeys,
        ];
        $list    = $this->getClient()->listObjects($bucket, $options);
        $files   = [];
        foreach ($list->getObjectList() as $object) {
            $url     = $this->getClient()->signUrl($bucket, $object->getKey(), 3600); // 获取文件 URL
            $files[] = [
                'filename'  => $object->getKey(),
                'url'       => $url,
                'path'      => '/',
                'is_hidden' => FALSE,
                'location'  => 'public',
            ];
        }
        return $files;
    }
}
