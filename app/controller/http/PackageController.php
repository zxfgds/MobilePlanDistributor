<?php

namespace app\controller\http;


use app\logic\ProductPackageLogic;

class PackageController extends Controller
{
    protected string $logic = ProductPackageLogic::class;
}