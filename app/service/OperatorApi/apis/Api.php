<?php

namespace app\service\OperatorApi\apis;
abstract class Api
{
    
    /**
     * 当前接口文件完成状态
     * @var bool
     */
    public static bool $apiComplete = FALSE;
    
    
    public function __construct()
    {
    }
    
    //推送订单
    abstract public function push();
    
    abstract public function modify();
    
    //重新推送
    abstract public function rePush();
    
    
    /**
     * 获取手机号码
     *
     * @param array $params
     *
     * @return array
     */
    abstract public function getNumber(array $params = []): array;
    
    //订单信息校验
    abstract public function valid();
    
    // 订单查询
    abstract public function query();
    
    // 取消订单
    abstract public function cancel();
    
    // 物流查询
    abstract public function express();
    
    //返回码对应
    abstract public function codeBind();
    
    abstract public function format();
    
    /**
     * 返回系统订单状态码
     *
     * @param $code
     *
     * @return int
     */
    abstract public function getSystemCode($code): int;
    
    /**
     * 状态码对照表
     * @return array
     */
    abstract public function codeMsgMap(): array;
    
    /**
     * 属性加签
     *
     * @param array $orderData
     *
     * @return array
     */
    abstract protected function makeSign(array $orderData): array;
    
    protected function http()
    {
    
    }
}