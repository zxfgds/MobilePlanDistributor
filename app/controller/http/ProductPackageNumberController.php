<?php

namespace app\controller\http;

use app\logic\ProductPackageNumberLogic;

class ProductPackageNumberController extends PhoneNumberController
{
    protected string $logic = ProductPackageNumberLogic::class;
}