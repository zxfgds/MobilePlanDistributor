<?php

namespace app\controller\http;

use app\logic\AgreementLogic;
use RedisException;
use support\Response;

class AgreementController extends Controller
{
    protected string $logic = AgreementLogic::class;
    
    /**
     * @return Response
     * @throws RedisException
     */
    public function detail(): Response
    {
        $data = $this->params();
        
        if (empty($data['id'])) {
            $detail = AgreementLogic::getOne(['is_sys_privacy' => 1]);
            return $this->success($detail);
        }
        return parent::detail();
    }
}