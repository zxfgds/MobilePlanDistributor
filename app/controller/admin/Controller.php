<?php

namespace app\controller\admin;

use app\controller\Controller as BaseController;
use app\library\HttpCode;
use app\library\Validate;
use Illuminate\Support\Arr;
use support\Log;
use support\Response;

class Controller extends BaseController
{
    
    /**
     * 获取数据列表
     *
     * @return Response 返回响应结果
     */
    public function getList(): Response
    {
        $params = $this->params();
        
        // 从参数中获取页码、每页数量、排序字段、排序方式等信息
        $page      = Arr::pull($params, 'pageNo') ?? 1;
        $pageSize  = Arr::pull($params, 'pageSize') ?? 20;
        $sortBy    = Arr::pull($params, 'sortBy') ?? 'id';
        $sortOrder = Arr::pull($params, 'sortOrder') ?? 'asc';
        
        // 调用逻辑层的 getList 方法，获取数据列表
        try {
            $list = call_user_func([$this->logic, 'getList'], $params, $pageSize, $page, $sortBy, $sortOrder);
            return $this->success($list); // 成功则返回数据列表
        } catch (\Throwable $e) {
            if (config('app.debug')) {
                $msg = sprintf(
                    "[%s][%s][%s:%s] %s\n%s",
                    __CLASS__,
                    __FUNCTION__,
                    $e->getFile(),
                    $e->getLine(),
                    $e->getMessage(),
                    $e->getTraceAsString()
                );
                Log::error($msg); // 记录异常日志
                return $this->error($msg); // 返回详细的错误响应
            } else {
                Log::error($e->getMessage()); // 记录异常日志
                return $this->error($e->getMessage()); // 返回简略的错误响应
            }
        }
    }
    
    
    /**
     * 获取单条数据详情
     *
     * @return Response 返回响应结果
     */
    public function detail(): Response
    {
        $params = $this->params(); // 获取参数数据
        
        if (empty($params['id'])) {
            return $this->error('缺少ID');
        }
        
        try {
            $res = call_user_func([$this->logic, 'getOne'], $params['id']); // 获取单条数据详情
            return $this->success($res); // 返回成功响应
        } catch (\Exception $e) {
            return $this->error($e->getMessage()); // 捕获异常，返回错误响应
        }
    }
    
    /**
     * 执行编辑操作
     *
     * @return Response 返回响应结果
     */
    public function doEdit(): Response
    {
        $params = $this->params(); // 获取参数数据
        
        // 检查必填字段是否存在
        $rules = $this->rules;
        if (!empty($rules)) {
            // 如果是更新 , 则判断是否存在 该 字段,如果不更新该字段,则无需判断
            if (Arr::get($params, 'id')) {
                foreach ($rules as $key => $rule) {
                    if (!array_key_exists($key, $params)) {
                        unset($rules[$key]);
                    }
                }
            }
            $errors = Validate::check($params, $rules);
            if ($errors) {
                return $this->error($errors, HttpCode::MISSING_REQUIRED_FIELD);
            }
        }
        
        $id     = Arr::pull($params, 'id') ?? 0; // 获取 ID
        $method = $id ? 'modify' : 'create'; // 根据 ID 判断是执行修改操作还是创建操作
        
        try {
            // 如果是  新增 记录 补全缺少的默认数据
            $params = $method == "create" ? $this->logic::setDefaultData($params) : $params;
            
            $data   = $id ? [$id, $params] : [$params]; // 根据是否存在 ID，组装参数
            $result = $this->logic::$method(...$data); // 调用逻辑层对应的方法，执行操作
            
            if ($result) {
                return $this->success(['id' => $id ?? $result]); // 成功则返回成功响应
            } else {
                return $this->error(); // 失败则返回错误响应
            }
        } catch (\Throwable $e) {
            if (config('app.debug')) {
                $msg = sprintf(
                    "[%s][%s][%s:%s] %s\n%s",
                    __CLASS__,
                    __FUNCTION__,
                    $e->getFile(),
                    $e->getLine(),
                    $e->getMessage(),
                    $e->getTraceAsString()
                );
                Log::error($msg); // 记录异常日志
                return $this->error($msg); // 返回详细的错误响应
            } else {
                Log::error($e->getMessage()); // 记录异常日志
                return $this->error($e->getMessage()); // 返回简略的错误响应
            }
        }
    }
    
    
    /**
     * @return Response
     */
    public function doDelete(): Response
    {
        $id     = request()->input('ids');
        var_dump($id);
        $result = call_user_func([$this->logic, 'delete'], $id);
        
        if ($result) {
            return $this->success([]);
        } else {
            return $this->error();
        }
    }
    
    public function getSupports(): Response
    {
        return $this->success();
    }
}