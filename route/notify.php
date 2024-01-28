<?php
use Webman\Route;

Route::disableDefaultRoute();

$routes = [];


foreach ($routes as $route => $controller) {
    Route::any("/client/{$route}/{action}", [$controller, 'action']);
}