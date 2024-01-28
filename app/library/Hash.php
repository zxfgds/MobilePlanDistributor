<?php

namespace app\library;
/**
 * 哈希类
 */
class Hash
{
    /**
     * 生成哈希值
     *
     * @param string $string 需要哈希的字符串
     * @param string $salt 盐，用于增加哈希的复杂度
     *
     * @return string 生成的哈希值
     */
    public static function make(string $string, string $salt = ''): string
    {
        return password_hash($string . $salt, PASSWORD_DEFAULT);
    }
    
    /**
     * 校验字符串与哈希值是否匹配
     *
     * @param string $string 需要校验的字符串
     * @param string $hash 哈希值
     * @param string $salt 盐，用于增加哈希的复杂度
     *
     * @return bool 如果匹配，返回 true；否则返回 false
     */
    public static function check(string $string, string $hash, string $salt = ''): bool
    {
        return password_verify($string . $salt, $hash);
    }
}