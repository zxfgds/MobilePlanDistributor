<?php

namespace app\logic;

use app\model\OperatorApi;
use Exception;
use Illuminate\Support\Str;
use Overtrue\Pinyin\Pinyin;

class OperatorApiLogic extends BaseLogic
{
    protected static string $model = OperatorApi::class;
    
    protected static bool $useCache = TRUE;
    
    /**
     * 修改指定条件或id的数据
     *
     * @param int|array|string $idOrCondition 目标数据的id或查询条件
     * @param array            $data          要修改的数据数组
     *
     * @return bool 是否成功修改数据
     * @throws Exception
     */
    public static function modify(int|array|string $idOrCondition, array $data): bool
    {
        if (empty($data['class_name'])) {
            $data['class_name'] = static::createClassName($data['name']);
        }
        var_dump($data['class_name']);
        static::createClassFile($data['class_name'], $data['name']);
        return parent::modify($idOrCondition, $data);
    }
    
    /**
     * 生成类名
     *
     * @param string|null $name 类名前缀
     *
     * @return string 返回一个新的唯一的类名
     */
    protected static function createClassName(?string $name = NULL): string
    {
        // 使用 Pinyin 将中文转换为拼音格式，并使用 '_' 将其分隔开
        $pinyin = Pinyin::permalink($name ?? '', '_');
        
        // 使用 Str::camel 将字符串转换为驼峰格式，然后将其首字母大写并加上 '_' 前缀
        $prefix = ucfirst(Str::camel($pinyin)) . "_";
        
        // 如果 prefix 为空，则使用默认的前缀 'Api_'
        $prefix ??= "Api_";
        
        // 返回以 prefix 开头的唯一 ID
        return "{$prefix}" . uniqid();
    }
    
    /**
     * Create a class file for given class name and name.
     *
     * @param string $className The class name to be created.
     * @param string $name      The name of the class.
     *
     * @return bool Returns true on successful creation of class file, otherwise false.
     */
    protected static function createClassFile(string $className, string $name): bool
    {
        // Get the path of the class file to be created.
        $path = app_path('service/OperatorApi/Providers/' . $className . '.php');
        
        // Return true if the class file already exists.
        if (is_file($path)) {
            return TRUE;
        }
        
        // Get the stub file of the API.
        $stub = app_path('service/OperatorApi/Providers/api.stub');
        
        // Get the content of the stub file and replace class and name placeholders with actual values.
        $content = file_get_contents($stub);
        $content = str_replace(['DUMMY_CLASS_NAME', 'DUMMY_NAME'], [$className, $name], $content);
        // Open the stream and write the content to the class file.
        file_put_contents($path, $content);
        
        // Return true on successful creation of class file.
        return TRUE;
    }
    
}