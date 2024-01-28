<?php

use Webman\Route;

Route::disableDefaultRoute();

\app\library\Router::init();

Route::any('/test', [\app\controller\TestController::class, 'test']);