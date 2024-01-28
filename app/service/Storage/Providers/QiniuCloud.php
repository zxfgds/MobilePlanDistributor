<?php

namespace app\service\Storage\Providers;

use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

class QiniuCloud extends Serve
{

    
    /**
     * Bucket 名称
     *
     * @var string
     */
    private string $bucket;
    
    public function __construct()
    {
        parent::__construct();
        
    }
    
    /**
     * 获取七牛云存储空间中的文件列表
     *
     * @param int $page     页码
     * @param int $pageSize 每页数量
     *
     * @return array|string 返回七牛云存储空间中的文件列表，如果操作失败则返回错误信息
     */
    public function getBucketFiles(int $page = 1, int $pageSize = 100): array|string
    {
        try {
            $auth      = new Auth($this->settings['accessKey'], $this->settings['secretKey']);
            $bucketMgr = new BucketManager($auth);
            $marker    = ($page - 1) * $pageSize;
            list($items, $marker, $err) = $bucketMgr->listFiles($this->settings['bucket'], NULL, NULL, $marker, $pageSize);
            
            return $items;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return $e->getMessage();
        }
    }
    
    /**
     * 删除七牛云存储空间中的文件
     *
     * @param string $fileKey 文件在七牛云存储空间中的名称
     *
     * @return string 如果操作成功，返回 "success"；如果操作失败，则返回错误信息
     */
    public function deleteFile(string $fileKey): string
    {
        try {
            $auth      = new Auth($this->settings['accessKey'], $this->settings['secretKey']);
            $bucketMgr = new BucketManager($auth);
            $err       = $bucketMgr->delete($this->settings['bucket'], $fileKey);
            
            return $err == NULL ? "success" : $err->message();
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return FALSE;
        }
    }
    
    /**
     * 上传文件到七牛云存储空间
     *
     *
     * @return array|bool 如果上传成功，返回 "success"；如果操作失败，则返回错误信息
     */
    public function uploadFile(): array|bool
    {
        try {
            $auth      = new Auth($this->settings['accessKey'], $this->settings['secretKey']);
            $uploadMgr = new UploadManager();
            $putPolicy = [
                "scope"    => $this->settings['bucket'],
                "deadline" => time() + 3600,
            ];
            $token     = $auth->uploadToken($this->settings['bucket'], NULL, 3600, $putPolicy);
            $filePath  = $this->filePath;
            $key       = $this->newPath . '/' . $this->newFileName;
            $result    = $uploadMgr->putFile($token, $key, $filePath);
            
            // 上传文件到本地存储空间的代码
            if ($result[0] !== NULL) {
                return [
                    'path'     => $this->newPath,
                    'filename' => $this->newFileName,
                ];
            } else {
                $this->error = "七牛云上传失败";
                return FALSE;
            }
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return FALSE;
        }
    }
    
}
