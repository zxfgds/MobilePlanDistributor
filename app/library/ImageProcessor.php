<?php

namespace app\library;

use Imagick;
use ImagickException;

class ImageProcessor
{
    /**
     * 压缩图片
     *
     * @param string $inputImagePath 输入图片路径
     *
     * @return string 返回处理后的图片路径
     * @throws ImagickException
     */
    public static function compressImage(string $inputImagePath): string
    {
        $outputImagePath = sys_get_temp_dir() . '/compressed_' . time() . '.jpg';
        
        $imagick = new Imagick($inputImagePath);
        $imagick->stripImage();
        $imagick->resizeImage(720, 0, Imagick::FILTER_LANCZOS, 1);
        $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
        $imagick->setImageCompressionQuality(75);
        $imagick->writeImage($outputImagePath);
        
        return $outputImagePath;
    }
    
    
    /**
     * 切割图片
     *
     * @param string    $inputImagePath 输入图片路径
     * @param float|int $headerOffset
     *
     * @return array
     * @throws ImagickException
     */
    public static function splitImage(string $inputImagePath, float|int $headerOffset = 0): array
    {
        // Calculate the height ratio between the original image and the compressed image
        $originalImage       = new Imagick($inputImagePath);
        $originalImageHeight = $originalImage->getImageHeight();
        
        $compressedImagePath = self::compressImage($inputImagePath);
        
        $compressedImage       = new Imagick($compressedImagePath);
        $compressedImageHeight = $compressedImage->getImageHeight();
        
        $heightRatio = $originalImageHeight / $compressedImageHeight;
        
        // Calculate the header offset for the compressed image
        $compressedHeaderOffset = $headerOffset > 0 ? intval($headerOffset / $heightRatio) : 0;
        
        // Handle header part if the header offset is greater than 0
        $headerImagePath = '';
        if ($compressedHeaderOffset > 0) {
            $headerImage = clone $compressedImage;
            $headerImage->cropImage($compressedImage->getImageWidth(), $compressedHeaderOffset, 0, 0);
            $headerImage->setImagePage(0, 0, 0, 0);
            $headerImagePath = sys_get_temp_dir() . '/header_' . time() . '.jpg';
            $headerImage->writeImage($headerImagePath);
        }
        
        $maxHeight   = ceil($compressedImageHeight / 6);
        $splitImages = [];
        
        // Start splitting from the compressedHeaderOffset
        for ($offset = $compressedHeaderOffset; $offset < $compressedImageHeight; $offset += $maxHeight) {
            $clonedImage = clone $compressedImage;
            $clonedImage->cropImage($compressedImage->getImageWidth(), $maxHeight, 0, $offset);
            $clonedImage->setImagePage(0, 0, 0, 0);
            $splitImagePath = sys_get_temp_dir() . '/body_' . time() . '_' . $offset . '.jpg';
            $clonedImage->writeImage($splitImagePath);
            $splitImages[] = $splitImagePath;
        }
        
        // Return an array with header and images keys
        return [
            'header' => $headerImagePath,
            'images' => $splitImages,
        ];
    }
    
    
}
