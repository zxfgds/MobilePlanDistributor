<?php

namespace app\library;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

// 压缩解压库
class Compression
{
    /**
     * 压缩方法
     *
     * @param string|array $inputPath 输入文件夹路径或多个文件的数组
     *
     * @return string 返回压缩文件路径
     */
    public static function compress(mixed $inputPath): string
    {
        $zipArchive         = new ZipArchive();
        $compressedFilePath = sys_get_temp_dir() . '/compressed_' . time() . '.zip';
        $zipArchive->open($compressedFilePath, ZipArchive::CREATE);
        
        if (is_array($inputPath)) {
            foreach ($inputPath as $file) {
                $zipArchive->addFile($file, basename($file));
            }
        } else {
            $directoryIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($inputPath));
            foreach ($directoryIterator as $file) {
                if (!$file->isDir()) {
                    $zipArchive->addFile($file, substr($file, strlen($inputPath) + 1));
                }
            }
        }
        
        $zipArchive->close();
        return $compressedFilePath;
    }
    
    /**
     * 解压方法
     *
     * @param string $compressedFilePath 输入压缩包路径
     *
     * @return string 返回解压后的文件夹路径
     */
    public static function decompress(string $compressedFilePath): string
    {
        $zipArchive = new ZipArchive();
        $zipArchive->open($compressedFilePath);
        $uncompressedFolderPath = sys_get_temp_dir() . '/uncompressed_' . time();
        $zipArchive->extractTo($uncompressedFolderPath);
        $zipArchive->close();
        return $uncompressedFolderPath;
    }
}