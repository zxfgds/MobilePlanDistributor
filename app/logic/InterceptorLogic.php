<?php

namespace app\logic;

use app\model\Interceptor;

class InterceptorLogic extends BaseLogic
{
    protected static string $model = Interceptor::class;
    
    protected static bool $useCache = TRUE;
}