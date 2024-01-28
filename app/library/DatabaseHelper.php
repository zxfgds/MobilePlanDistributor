<?php

namespace app\library;

class DatabaseHelper
{
    /**
     * 导出数据库到SQL文件
     *
     * @return bool 导出是否成功
     */
    public static function backup(): bool
    {
        
        $username     = env("DB_USERNAME");
        $databaseName = env("DB_DATABASE");
        $password     = env("DB_PASSWORD");
        $filePath     = databasePath('sql/' . env("APP_NAME") . '.sql');
        // 命令行命令
        $command = "mysqldump --user={$username} --password={$password} --host=localhost {$databaseName} > {$filePath}";

        // 执行命令
        system($command, $result);
        
        // 返回导出是否成功
        return $result === 0;
    }
    
    /**
     * 导入SQL文件到数据库
     *
     * @return bool 导入是否成功
     */
    public static function import(): bool
    {
        $username     = env("DB_USERNAME");
        $databaseName = env("DB_DATABASE");
        $password     = env("DB_PASSWORD");
        $filePath     = databasePath('sql/' . env("APP_NAME") . '.sql');
        // 命令行命令
        $command = "mysql --user={$username} --password={$password} --host=localhost {$databaseName} < {$filePath}";
        
        // 执行命令
        system($command, $result);
        
        // 返回导入是否成功
        return $result === 0;
    }
    
}