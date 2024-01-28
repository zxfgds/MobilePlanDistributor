<?php

namespace app\library;

class RichText
{
    /**
     * 将HTML内容转换为数组
     *
     * @param string $content HTML内容
     *
     * @return array 转换后的数组
     */
    public static function htmlToArray(string $content): array
    {
        // 定义正则规则
        $patterns = [
            'link'   => '/<a href="(.*)" .*>.*<\/a>/Uis',
            'img'    => '/<img src="(.*)".*\/>/Uis',
            'pre'    => '/<pre.*>(.*)<\/pre>/Uis',
            'strong' => '/<strong.*>(.*)<\/strong>/Uis',
            'br'     => '/<br.*\/>/Uis',
            'quote'  => '/<blockquote>(.*)<\/blockquote>/Uis',
            'spline' => '/<hr(.*)\/>/Uis',
            'video'  => '/<video .*src="(.*)".*\/video>/Uis',
        ];
        
        // 使用正则替换规则处理内容
        foreach ($patterns as $type => $pattern) {
            $content = preg_replace($pattern, "--pgSuperSpliteGraceUI--{$type}::pgSuperSplite::\$1--pgSuperSpliteGraceUI--", $content);
        }
        
        // 去除标签、空白字符、特殊字符
        $content = strip_tags($content);
        $content = preg_replace('/(\t)/Uis', '', $content);
        $content = preg_replace('/&nbsp;/Uis', ' ', $content);
        $content = preg_replace('/&.*;/Uis', '', $content);
        
        // 拆分数组
        $content = explode('--pgSuperSpliteGraceUI--', $content);
        
        // 处理数组并生成内容数组
        $contentArray = array_filter($content, fn($item) => str_replace(["\r\n", "\r", "\n", ''], '', $item) !== '');
        // 添加居中标题
//        $contentArray[] = ['type' => 'center', 'content' => '居中标题'];
        
        return array_map(function ($item) {
            $itemArr = explode('::pgSuperSplite::', $item);
            if (count($itemArr) < 2) {
                return ['type' => 'txt', 'content' => trim($itemArr[0])];
            } else {
                return ['type' => trim($itemArr[0]), 'content' => trim($itemArr[1])];
            }
        }, $contentArray);
    }
    
    
}