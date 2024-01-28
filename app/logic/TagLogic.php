<?php

namespace app\logic;

use app\model\Tag;

class TagLogic extends BaseLogic
{
    protected static string $model    = Tag::class;
    protected static bool   $useCache = TRUE;
}