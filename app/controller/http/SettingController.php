<?php

namespace app\controller\http;

use app\logic\SettingLogic;

class SettingController extends Controller
{
    public function menus()
    {
        $settings = SettingLogic::get('index', 'menus');
        // todo 排序/ 状态 / shopId
        return $this->success($settings);
    }
}