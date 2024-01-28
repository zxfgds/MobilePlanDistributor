<?php

namespace app\service\Storage\Providers;

use Qcloud\Cos\Client;
use Qcloud\Cos\Exception\CosException;

class TencentCloud extends Serve
{
    
    /**
     * 获取 COS 客户端
     *
     * @return Client
     */
    private function getClient(): Client
    {
        $settings = $this->settings;
        
        return new Client([
            'region'      => $settings['region'],
            'credentials' => [
                'appId'     => $settings['appId'],
                'secretId'  => $settings['secretId'],
                'secretKey' => $settings['secretKey'],
            ],
            'http'        => [
                'timeout' => 60,
            ],
        ]);
    }
    
    /**
     * 获取 COS 存储桶中的文件列表
     *
     * @param int $page     页码
     * @param int $pageSize 每页数量
     *
     * @return array|string 返回 COS 存储桶中的文件列表，如果操作失败则返回错误信息
     */
    public function getBucketFiles(int $page = 1, int $pageSize = 100): array|string
    {
        try {
            $client = $this->getClient();
            return $client->listObjects(array(
                'Bucket'  => $this->settings['bucket'],
                'Marker'  => ($page - 1) * $pageSize,
                'MaxKeys' => $pageSize,
            ));
        } catch (CosException $e) {
            return $e->getMessage();
        }
    }
    
    /**
     * 删除 COS 存储桶中的文件
     *
     * @param string $fileKey 文件在 COS 存储桶中的名称
     *
     * @return string 如果操作成功，返回 "success"；如果操作失败，则返回错误信息
     */
    public function deleteFile(string $fileKey): string
    {
        try {
            $client = $this->getClient();
            $client->deleteObject(array(
                'Bucket' => $this->settings['bucket'],
                'Key'    => $fileKey,
            ));
            return 'success';
        } catch (CosException $e) {
            return $e->getMessage();
        }
    }
    
    /**
     * 上传文件到 COS 存储桶
     *
     * @return array|bool 如果上传成功，返回文件信息；如果操作失败，则返回 FALSE
     */
    public function uploadFile(): array|bool
    {
        try {
            $client = $this->getClient();
            $key    = path_combine($this->newPath, $this->newFileName);
            $result = $client->putObject([
                'Bucket' => $this->settings['bucket'],
                'Key'    => $key,
                'Body'   => fopen($this->filePath, 'rb'),
            ]);
            if ($result['statusCode'] === 200) {
                $url = $client->getObjectUrl($this->settings['bucket'], $key);
                return [
                    'path'     => $this->newPath,
                    'filename' => $this->newFileName,
                    'url'      => $url,
                ];
            } else {
                return FALSE;
            }
        } catch (CosException $e) {
            return FALSE;
        }
    }
}