<?php

namespace app\controller;


use app\library\HttpCode;
use app\library\Validate;
use app\logic\BaseLogic;
use Exception;
use Illuminate\Support\Arr;
use support\Log;
use support\Response;

class Controller
{
    protected string $logic = BaseLogic::class;
    private array    $params;
    
    /**
     * 入库检查规则
     * @var array
     */
    protected array $rules = [];
    
    /**
     * 保存时忽略字段(禁止修改)
     * @var array
     */
    protected array $ignoreOnSave = [];
    
    /**
     * @throws Exception
     */
    public function __construct()
    {
        if (!$this->logic) {
            throw new Exception('子类必须定义 $logic 属性');
        }
    }
    
    /**
     * @param $method
     * @param $args
     *
     * @return mixed
     * @throws Exception
     */
    public function __call($method, $args)
    {
        if (method_exists($this->logic, $method)) {
            return call_user_func_array([$this->logic, $method], $args);
        } else {
            Log::error(__CLASS__ . "系统错误");
            return $this->error('系统错误', HttpCode::SYSTEM_ERROR);
        }
    }
    
    public function params()
    {
        return $this->params ?? request()->all();
    }
    
    public function setParams($params): void
    {
        $this->params = $params;
    }
    
    public function action($action)
    {
        return $this->$action();
    }
    
    /**
     * 成功的返回
     *
     * @param array|string|null $data
     * @param string            $msg
     *
     * @return Response
     */
    public function success(array|null|string $data = [], string $msg = '操作成功'): Response
    {
        $result = [
            'code' => 0,
            'msg'  => $msg,
            'data' => $data ?? [],
        ];
        
        return json($result);
    }
    
    /**
     * 失败返回
     *
     * @param string|array $msg
     * @param int          $code
     * @param array        $data
     *
     * @return Response
     */
    public function error(string|array $msg = '操作失败', int $code = HttpCode::ERROR, array $data = []): Response
    {
        $result = [
            'code' => $code,
            'msg'  => is_array($msg) ? $msg[0] : $msg,
            'data' => $data,
        ];
        return json($result);
    }
}
