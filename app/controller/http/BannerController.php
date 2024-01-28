<?php

namespace app\controller\http;

use app\logic\BannerLogic;
use app\model\Banner;
use support\Response;

class BannerController extends Controller
{
    
    protected string $logic = BannerLogic::class;
    
    public function getPopUp(): Response
    {
        $data = Banner::where('status', 1)
                      ->where('type', Banner::TYPE_POPUP)
                      ->where('start_at', "<=", now())
                      ->where('end_at', ">=", now())
                      ->orderBy('id', 'desc')
                      ->first();
        
        return $this->success($data ? $data->toArray() : []);
    }
    
    
    /**
     * 获取首页广告横幅
     * @return Response
     */
    public function getIndexBanner(): Response
    {
        $data = Banner::where('status', 1)
                      ->where('type', Banner::TYPE_AD_INDEX)
                      ->where('start_at', "<=", now())
                      ->where('end_at', ">=", now())
                      ->orderBy('id', 'desc')
                      ->first();
        
        return $this->success($data ? $data->toArray() : []);
    }
}