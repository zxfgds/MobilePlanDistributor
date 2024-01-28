<?php

namespace plugin\webman\gateway;

use GatewayWorker\Lib\Gateway;

class Events
{
    public static function onWorkerStart($worker)
    {
    }
    
    public static function onConnect($client_id)
    {
        var_dump("connected : {$client_id} ");
    }
    
    public static function onWebSocketConnect($client_id, $data)
    {
        Gateway::sendToClient($client_id, "YOU ARE WELCOME! NOW LET ME SEE YOUR DATA !");
    }
    
    public static function onMessage($client_id, $message)
    {
        
        
        Gateway::sendToClient($client_id, "receive message $message");
    }
    
    public static function onClose($client_id)
    {
        var_dump("$client_id => FUCK OFF");
    }
    
}
