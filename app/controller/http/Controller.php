<?php

namespace app\controller\http;

use Illuminate\Support\Arr;
use support\Log;
use support\Response;

class Controller extends \app\controller\Controller
{
    
    public function __construct()
    {
        parent::__construct();
    }
    
    
    /**
     * 获取数据列表
     *
     * @return Response 返回响应结果
     */
    public function getList(): Response
    {
        $params = $this->params();

        // 从参数中获取页码、每页数量、排序字段、排序方式等信息
        $page      = Arr::pull($params, 'page') ?? 1;
        $pageSize  = Arr::pull($params, 'pageSize') ?? 20;
        $sortBy    = Arr::pull($params, 'sortBy') ?? 'id';
        $sortOrder = Arr::pull($params, 'sortOrder') ?? 'asc';
        // 调用逻辑层的 getList 方法，获取数据列表
        try {
            $list = call_user_func([$this->logic, 'clientGetList'], $params, $pageSize, $page, $sortBy, $sortOrder);
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
            $res = call_user_func([$this->logic, 'clientGetOne'], $params['id']); // 获取单条数据详情
            return $this->success($res); // 返回成功响应
        } catch (\Exception $e) {
            return $this->error($e->getMessage()); // 捕获异常，返回错误响应
        }
    }
    
}