<?php

namespace app\service\Storage\Providers;

use Exception;

class Serve
{
    
    
    protected string $storage;
    protected string $filePath;
    
    /**
     * 新文件名
     *
     * @var string
     */
    protected string $newFileName;
    
    protected array $settings;
    /**
     * 新的路径
     *
     * @var string
     */
    protected string $newPath;
    
    protected string $error;
    
    public function __construct()
    {
        $this->newPath = date('Ymd');
    }
    
    /**
     * 生成新文件名
     *
     * @return void
     * @throws Exception
     */
    protected function generateNewFileName(): void
    {
        $fileInfo          = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType          = $fileInfo->file($this->filePath);
        $this->newFileName = $this->generateRandomFileName($mimeType);
    }
    
    
    /**
     * 生成随机文件名的方法
     *
     * @param string $mimeType 文件的mime类型
     *
     * @return string $newFileName 生成的随机文件名
     * @throws Exception
     */
    function generateRandomFileName(string $mimeType): string
    {
        $timestamp    = time();
        $randomString = bin2hex(random_bytes(8));
        
        $mimeMap   = [
            'image/jpeg'                                                                => 'jpg',
            'image/png'                                                                 => 'png',
            'image/gif'                                                                 => 'gif',
            'application/pdf'                                                           => 'pdf',
            'application/msword'                                                        => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
            'application/vnd.ms-excel'                                                  => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
            'application/vnd.ms-powerpoint'                                             => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'text/plain'                                                                => 'txt',
            'text/html'                                                                 => 'html',
            'application/json'                                                          => 'json',
            'application/xml'                                                           => 'xml',
        ];
        $extension = in_array($mimeType, array_keys($mimeMap)) ? $mimeMap[$mimeType] : "unknown";
        return $timestamp . '_' . $randomString . '.' . $extension;
    }
    
    /**
     * @param array $settings
     *
     * @return $this
     */
    public function setSettings(array $settings): static
    {
        $this->settings = $settings;
        return $this;
    }
    
    public function setFile($filePath): static
    {
        $this->filePath = $filePath;
        $this->generateNewFileName();
        return $this;
    }
    
    /**
     * 获取错误信息
     *
     * @return string|null 返回错误信息，如果没有错误则返回 null
     */
    public function getError(): string|null
    {
        return $this->error;
    }
}