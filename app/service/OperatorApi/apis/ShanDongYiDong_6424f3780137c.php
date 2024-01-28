<?php

namespace app\service\OperatorApi\apis;

use Illuminate\Support\Arr;

//山东移动
class ShanDongYiDong_6424f3780137c extends Api
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
    public function getNumber(array $params = []): array
    {
        $phone_prefixes = ['130', '131', '132', '133', '134', '135', '136', '137', '138', '139', '141', '142', '143', '144', '146', '147', '148', '149', '150', '151', '152', '153', '155', '156', '157', '158', '159', '166', '167', '170', '171', '172', '173', '174', '175', '176', '177', '178', '180', '181', '182', '183', '184', '185', '186', '187', '188', '189', '190', '191', '192', '193', '194', '195', '196', '197', '198', '199'];
        $area_codes     = [
            '0310', '0311', '0312', '0313', '0314', '0315', '0316', '0317', '0318', '0319', '0330', '0331', '0332', '0333', '0334', '0335', '0349', '0350', '0351', '0352', '0353', '0354', '0355', '0356', '0357', '0358', '0359', '0360', '0361', '0362', '0370', '0371', '0372', '0373', '0374', '0375', '0376', '0377', '0378', '0379', '0391', '0392', '0393', '0394', '0395', '0396', '0398', '0411', '0412', '0413', '0414', '0415', '0416', '0417', '0418', '0419', '0421', '0427', '0429', '0431', '0432', '0433', '0434', '0435', '0436', '0437', '0438', '0439', '0451', '0452', '0453', '0454', '0455', '0456', '0457', '0458', '0459', '0464', '0467', '0468', '0469', '0470', '0471', '0472', '0473', '0474', '0475', '0476', '0477', '0478', '0479', '0482', '0483', '0487', '0510', '0511', '0512', '0513', '0514', '0515', '0516', '0517', '0518', '0519', '0523', '0527', '0530', '0531', '0532', '0533', '0534', '0535', '0536', '0537', '0538', '0539', '0543', '0546', '0550', '0551', '0552', '0553', '0554', '0555', '0556', '0557', '0558', '0559', '0561', '0562', '0563', '0564', '0566', '0570', '0571', '0572', '0573', '0574', '0575', '0576', '0577', '0578', '0579', '0580', '0581', '0582', '0591', '0592', '0593', '0594', '0595', '0596', '0597', '0598', '0599',
        ];
        $page           = Arr::pull($params, 'page', 1);
        $pageSize       = Arr::pull($params, 'pageSize', 10);
        $array          = [];
        for ($i = 0; $i < $pageSize; $i ++) {
            $array[] = $phone_prefixes[array_rand($phone_prefixes)] . $area_codes[array_rand($area_codes)] . mt_rand(1000, 9999);
        }
        return $array;
    }
    
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
}