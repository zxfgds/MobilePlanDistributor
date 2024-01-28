<?php

namespace app\service\OperatorApi;

use Exception;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;


/**
 * @method push()
 * @method modify()
 * @method rePush()
 * @method getNumber(array $params = [])
 * @method valid()
 * @method query()
 * @method cancel()
 * @method express()
 * @method codeBind()
 * @method format()
 * @method getSystemCode($code)
 * @method codeMsgMap()
 */
class Service
{
    protected string $class;
    private object   $apiInstance;
    
    /**
     * 创建一个新的实例
     *
     * @param string $className 类名
     *
     * @throws InvalidArgumentException|ReflectionException 如果类文件不存在或者类本身不存在
     */
    public function __construct(string $className)
    {
        // 检查类名是否为空
        $classFilePath = __DIR__ . '/Providers/' . $className . '.php';
        // 补全命名空间
        $fullClassName = "app\\service\\OperatorApi\\Providers\\" . $className;
        
        // 检查文件是否存在
        if (!file_exists($classFilePath)) {
            throw new InvalidArgumentException("Class file '{$classFilePath}' does not exist.");
        }
        
        // 包含目标类文件
        include_once $classFilePath;
        try {
            // 通过类名创建反射类
            $reflectionClass = new ReflectionClass($fullClassName);
        } catch (ReflectionException $e) {
            throw new InvalidArgumentException("Class '{$fullClassName}' does not exist.", 0, $e);
        }
        // 使用反射机制实例化目标类
        $this->apiInstance = $reflectionClass?->newInstance();
    }
    
    /**
     * 通过魔术方法调用API实例中的方法
     *
     * @param string $name      被调用的方法名
     * @param array  $arguments 方法的参数数组
     *
     * @return mixed 调用得到的结果
     * @throws Exception
     */
    public function __call(string $name, array $arguments): mixed
    {
        if (method_exists($this->apiInstance, $name)) {
            return call_user_func_array([$this->apiInstance, $name], $arguments);
        } else {
            throw new Exception("Method {$name} does not exist in the target class.");
        }
    }
}