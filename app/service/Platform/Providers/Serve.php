<?php

namespace app\service\Platform\Providers;

use app\library\RedisCache;
use Illuminate\Support\Str;
use RedisException;

class Serve
{
    /**
     * @return string
     * @throws RedisException
     */
    public function createState(): string
    {
        $state = base64_encode(Str::random(32));
        RedisCache::put($state, 1, 5);
        return $state;
    }

    /**
     * @throws RedisException
     */
    public function checkState($state)
    {
        return RedisCache::pull($state);
    }
}