<?php

namespace app\exception;

use support\Log;
use Webman\Exception\ExceptionHandler;
use Webman\Http\Response;


class CustomExceptionHandler extends ExceptionHandler
{
    public function report(\Throwable $exception): void
    {
        // Save the error log to file or database.
        Log::error($exception);
    }
    
    public function render($request, \Throwable $exception): Response
    {
        if (env('APP_DEBUG')) {
            $response = [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
                'data'    => $exception instanceof CustomException ? $exception->getDetails() : [],
            ];
        } else {
            $response = [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
            ];
        }
        
        return json($response);
    }
}