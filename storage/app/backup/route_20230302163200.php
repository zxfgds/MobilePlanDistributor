<?php

use Webman\Route;

Route::disableDefaultRoute();


Route::any('/test', [\app\controller\TestController::class, 'test']);
/**
 * 管理路由
 */
Route::group("/" . getenv("ADMIN_PREFIX"), function () {
//ADMIN_START
    Route::any('/test', function () {
        $user = \app\logic\AdminUserLogic::getOne(1);
        return json($user);
    });
    // 管理员用户
    Route::group('/user', function () {
        Route::any('/{action}', [\app\controller\admin\AdminUserController::class, 'action']);
    });
    /**
     * 路由
     */
    Route::group('/router', function () {
        Route::any('/{action}', [\app\controller\admin\AdminMenuController::class, 'action']);
    });
    
    /**
     * 套餐
     */
    Route::group('/package', function () {
        Route::any('/{action}', [\app\controller\admin\ProductPackageController::class, 'action']);
    });
    Route::group('/package-number', function () {
        Route::any('/{action}', [\app\controller\admin\PackageNumberStoreController::class, 'action']);
    });
    Route::group('/package-number-store', function () {
        Route::any('/{action}', [\app\controller\admin\PackageNumberStoreController::class, 'action']);
    });
    /**
     * 设置
     */
    Route::group('/settings', function () {
        Route::any('/{action}', [\app\controller\admin\SettingController::class, 'action']);
    });
    /**
     * 其他
     */
    
    Route::group('/agreement', function () {
        Route::any('/{action}', [\app\controller\admin\AgreementController::class, 'action']);
    });
    
    Route::group('/support', function () {
        Route::any('/{action}', [\app\controller\admin\SupportController::class, 'action']);
    });

//    文件上传
    Route::group('/file', function () {
        Route::any('/{action}', [\app\controller\admin\AttachmentController::class, 'action']);
    });
    //ADMIN_END
});


