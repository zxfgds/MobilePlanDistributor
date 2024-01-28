<?php

namespace app\controller\admin;

use app\logic\ProductPackageNumberLogic;

class ProductPackageNumberController extends NumberController
{
    protected string $logic = ProductPackageNumberLogic::class;
}