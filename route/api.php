<?php
use Webman\Route;
Route::disableDefaultRoute();

Route::any("/api/get-number-patterns", [\app\controller\admin\PhoneNumberPatternController::class, 'getMatcherList']);