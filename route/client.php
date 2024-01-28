<?php

use app\controller\admin\IndexMenuController;
use app\controller\http\AgreementController;
use app\controller\http\BannerController;
use app\controller\http\ClientConfigController;
use app\controller\http\ConfigController;
use app\controller\http\PackageController;
use app\controller\http\PhoneNumberController;
use app\controller\http\PhoneNumberPatternController;
use app\controller\http\RegionController;
use app\controller\http\SettingController;
use app\controller\http\UserController;
use Webman\Route;

Route::disableDefaultRoute();

$routes = [
	'banner'     => BannerController::class,
	'index-menu' => IndexMenuController::class,
	'settings'   => SettingController::class,
	'package'    => PackageController::class,
	'number'     => PhoneNumberController::class,
	'region'     => RegionController::class,
	'pattern'    => PhoneNumberPatternController::class,
	'config'     => ClientConfigController::class,
	'user'       => UserController::class,
	'agreement'  => AgreementController::class,
];


foreach ($routes as $route => $controller) {
	Route::any("/client/{$route}/{action}", [$controller, 'action']);
}