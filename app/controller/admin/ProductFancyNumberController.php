<?php

namespace app\controller\admin;

use app\logic\ProductFancyNumberLogic;

class ProductFancyNumberController extends NumberController
{
    protected string $logic = ProductFancyNumberLogic::class;
    
    
}