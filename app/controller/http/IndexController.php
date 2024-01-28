<?php

namespace app\controller\http;

use app\logic\SettingLogic;
use support\Response;

class IndexController extends Controller
{
    public function menus(): Response
    {
        $settings = SettingLogic::get('index', 'menus');
        return $this->success($settings);
    }
}