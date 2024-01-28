<?php

namespace app\service\OperatorApi\Providers;

//山东移动
class ShanDongYiDong_6424f3780137c extends Base
{
    
    /**
     * @return mixed
     */
    public function push()
    {
        // TODO: Implement send() method.
    }
    
    /**
     * @return mixed
     */
    public function modify()
    {
        // TODO: Implement modify() method.
    }
    
    /**
     * @return mixed
     */
    public function rePush()
    {
        // TODO: Implement resend() method.
    }
    
    /**
     * @return mixed
     */
    /**
     * @return mixed
     */
    public function valid()
    {
        // TODO: Implement valid() method.
    }
    
    /**
     * @return mixed
     */
    public function query()
    {
        // TODO: Implement query() method.
    }
    
    /**
     * @return mixed
     */
    public function cancel()
    {
        // TODO: Implement cancel() method.
    }
    
    /**
     * @return mixed
     */
    public function express()
    {
        // TODO: Implement express() method.
    }
    
    /**
     * @return mixed
     */
    public function codeBind()
    {
        // TODO: Implement codeBind() method.
    }
    
    /**
     * @return mixed
     */
    public function format()
    {
        // TODO: Implement format() method.
    }
    
    /**
     * @param $code
     *
     * @return int
     */
    public function getSystemCode($code): int
    {
        // TODO: Implement getSystemCode() method.
    }
    
    /**
     * @return array
     */
    public function codeMsgMap(): array
    {
        // TODO: Implement codeMsgMap() method.
    }
    
    /**
     * @param array $orderData
     *
     * @return array
     */
    protected function makeSign(array $orderData): array
    {
        // TODO: Implement makeSign() method.
    }
    
    public function getNumber(array $params = []): array
    {
        $phone_prefixes = ['130', '131', '132', '133', '134', '135', '136', '137', '138', '139', '141', '142', '143', '144', '146', '147', '148', '149', '150', '151', '152', '153', '155', '156', '157', '158', '159', '166', '167', '170', '171', '172', '173', '174', '175', '176', '177', '178', '180', '181', '182', '183', '184', '185', '186', '187'];
    }
}