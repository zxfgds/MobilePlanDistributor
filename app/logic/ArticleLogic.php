<?php

namespace app\logic;

use app\model\Article;

class ArticleLogic extends BaseLogic
{
    protected static string $model = Article::class;
    protected static bool $useCache = TRUE;
}