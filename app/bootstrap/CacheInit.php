<?php

namespace app\bootstrap;

use Webman\Bootstrap;

class CacheInit implements Bootstrap
{
    public static function start($worker)
    {
        // Is it console environment ?
        $is_console = !$worker;
        if ($is_console) {
            // If you do not want to execute this in console, just return.
            return;
        }

    }
    
}
