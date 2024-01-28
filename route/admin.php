<?php

use app\controller\admin\AdminMenuController;
use app\controller\admin\AdminUserController;
use app\controller\admin\AgreementController;
use app\controller\admin\AttachmentController;
use app\controller\admin\AutoBidRuleController;
use app\controller\admin\BannerController;
use app\controller\admin\IndexMenuController;
use app\controller\admin\InterceptorController;
use app\controller\admin\OperatorApiController;
use app\controller\admin\PhoneNumberPatternController;
use app\controller\admin\PhoneNumberStoreController;
use app\controller\admin\ProductPackageController;
use app\controller\admin\ProductFancyNumberController;
use app\controller\admin\SettingController;
use app\controller\admin\SupportController;
use app\controller\admin\TagController;
use app\controller\admin\TaskController;
use app\controller\admin\ToolController;
use app\controller\http\RegionController;
use app\controller\TestController;
use Webman\Route;

Route::disableDefaultRoute();


$routes = [
    'user'               => AdminUserController::class, // 管理员用户
    'router'             => AdminMenuController::class, // 路由
    'package'            => ProductPackageController::class, // 套餐
    'virtual-number'     => ProductFancyNumberController::class, //虚拟靓号
    'phone-number-store' => PhoneNumberStoreController::class,//号库
    'settings'           => SettingController::class, // 设置
    'support'            => SupportController::class, // 支持
    'agreement'          => AgreementController::class, // 协议
    'tag'                => TagController::class, // 特长又是
    'interceptor'        => InterceptorController::class, // 拦截器
    'regions'            => RegionController::class, // 地区
    'file'               => AttachmentController::class, // 文件上传
    'banner'             => BannerController::class,// 幻灯,广告管理
    'index-menu'         => IndexMenuController::class, // 首页菜单
    'tool'               => ToolController::class, // 工具
    'pattern'            => PhoneNumberPatternController::class, // 号码正则规则
    'task'               => TaskController::class, // 任务
    'auto-bid-rule'      => AutoBidRuleController::class, // 自动加价规则
    'operator-api'       => OperatorApiController::class,// 运营商接口
];

//
//Route::any('/admin/test-file', [TestController::class, 'test']);

foreach ($routes as $route => $controller) {
    Route::any("/" . env("ADMIN_PREFIX") . "/{$route}/{action}", [$controller, 'action']);
}

