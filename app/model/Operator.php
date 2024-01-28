<?php

namespace app\model;

class Operator
{
    const YIDIONG = 1;
    const LIANTONG = 2;
    const DIANXIN = 3;
    const GUANGDIAN = 4;

    const OPERATORS = [
        self::YIDIONG   => '移动',
        self::LIANTONG  => '联通',
        self::DIANXIN   => '电信',
        self::GUANGDIAN => '广电',
    ];
}