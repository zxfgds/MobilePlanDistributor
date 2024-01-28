<?php

namespace app\service\storageDrivers;

use Exception;

class Local extends Serve
{
    /**
     * 是否公开文件
     *
     * @var bool
     */
    private bool $isPublic;
    
    /**
     * 构造函数
     *
     * @param bool $isPublic 是否公开文件
     */
    public function __construct(bool $isPublic)
    {
        parent::__construct();
        $this->isPublic = $isPublic;
    }
    
    /**
     * 上传文件到本地存储目录
     *
     *
     * @return array|bool
     */
    public function uploadFile(): bool|array
    {
        $destination = ($this->isPublic) ? "public" : "hidden";
        $destination = storagePath($destination . "/" . $this->newPath);
        checkAndCreateDirectory($destination);
        $saveFile = $destination . "/" . $this->newFileName;
        try {
            if (copy($this->filePath, $saveFile)) {
                return [
                    'path'     => $this->newPath,
                    'filename' => $this->newFileName,
                ];
            } else {
                $this->error = "本地保存失败";
                return FALSE;
            }
        } catch (Exception $e) {
            return FALSE;
        }
    }
}
