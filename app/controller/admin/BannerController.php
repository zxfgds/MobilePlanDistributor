<?php

namespace app\controller\admin;

use app\logic\BannerLogic;
use app\model\Banner;
use support\Response;

class BannerController extends Controller
{
    protected string $logic = BannerLogic::class;
    
    protected array $rules = [
        'name'     => ['required' => TRUE],
        'image'    => ['required' => TRUE],
        'start_at' => ['required' => TRUE],
    ];
    
    public function getSupports(): Response
    {
        return $this->success([
            'types' => Banner::TYPES,
        ]);
    }
    
//    public function
}