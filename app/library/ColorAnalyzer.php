<?php

namespace app\library;

use Imagick;
use ImagickException;

/**
 * 颜色分析类
 *
 * 该类使用ImageMagick分析图片，获取主色、文字颜色、按钮颜色等信息。
 */
class ColorAnalyzer
{
    /**
     * 获取主色
     *
     * @param mixed $images 图片路径数组或图片地址
     *
     * @return string 16进制颜色代码
     * @throws ImagickException
     */
    public static function getMainColor(mixed $images): string
    {
        if (!is_array($images)) {
            $images = [$images];
        }
        
        $hexColors = [];
        
        // 读取所有图片
        foreach ($images as $image) {
            $img = new Imagick();
            $img->readImage($image);
            $img->quantizeImage(256, Imagick::COLORSPACE_RGB, 0, FALSE, FALSE);
            $img->uniqueImageColors();
            $pixels = $img->getImageHistogram();
            
            // 分析像素数据，统计每种颜色的数量
            foreach ($pixels as $pixel) {
                $color    = $pixel->getColor();
                $hexColor = sprintf('#%02x%02x%02x', $color['r'], $color['g'], $color['b']);
                if (!isset($hexColors[$hexColor])) {
                    $hexColors[$hexColor] = 0;
                }
                $hexColors[$hexColor] += $pixel->getColorCount();
            }
        }
        
        // 按数量排序，获取数量最多的颜色
        arsort($hexColors);
        return array_key_first($hexColors);
    }
    
    /**
     * 获取文字颜色
     *
     * @param array $images 图片路径数组
     *
     * @return string 16进制颜色代码
     * @throws ImagickException
     */
    public static function getTextColor(array $images): string
    {
        $mainColor = self::getMainColor($images);
        
        // 计算与白色的比较值，选择比较值小的颜色作为文字颜色
        $mainColorBrightness = (hexdec(substr($mainColor, 1, 2)) * 0.299 + hexdec(substr($mainColor, 3, 2)) * 0.587 + hexdec(substr($mainColor, 5, 2)) * 0.114) / 255;
        return $mainColorBrightness > 0.5 ? '#000000' : '#FFFFFF';
    }
    
    /**
     * 获取按钮颜色
     *
     * @param array $images 图片路径数组
     *
     * @return array 包含按钮颜色和按钮文字颜色的数组
     * @throws ImagickException
     */
    public static function getButtonColor(array $images): array
    {
        $mainColor = self::getMainColor($images);
        
        // 计算与白色的比较值，选择比较值大的颜色作为按钮颜色
        $mainColorBrightness = (hexdec(substr($mainColor, 1, 2)) * 0.299 + hexdec(substr($mainColor, 3, 2)) * 0.587 + hexdec(substr($mainColor, 5, 2)) * 0.114) / 255;
        return $mainColorBrightness > 0.5 ? '#FFFFFF' : '#000000';
        
    }
    
    /**
     * 获取按钮颜色
     *
     * @param array $images 图片路径数组
     *
     * @return array 按钮文字颜色的数组
     * @throws ImagickException
     */
    public static function getButtonTextColor(array $images): array
    {
        $mainColor = self::getMainColor($images);
        
        // 计算与白色的比较值，选择比较值大的颜色作为按钮颜色
        $mainColorBrightness = (hexdec(substr($mainColor, 1, 2)) * 0.299 + hexdec(substr($mainColor, 3, 2)) * 0.587 + hexdec(substr($mainColor, 5, 2)) * 0.114) / 255;
        $buttonColor         = $mainColorBrightness > 0.5 ? '#FFFFFF' : '#000000';
        
        // 计算与黑色的比较值，选择比较值小的颜色作为按钮文字颜色
        $buttonColorBrightness = (hexdec(substr($buttonColor, 1, 2)) * 0.299 + hexdec(substr($buttonColor, 3, 2)) * 0.587 + hexdec(substr($buttonColor, 5, 2)) * 0.114) / 255;
        return $buttonColorBrightness > 0.5 ? '#000000' : '#FFFFFF';
    }
    
}