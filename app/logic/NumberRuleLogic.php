<?php

namespace app\logic;

use app\model\NumberRule;

class NumberRuleLogic extends BaseLogic
{
    protected static string $model = NumberRule::class;

    public static function getAll()
    {
        $rules = self::$model::all();

        var_dump($rules);
    }
}