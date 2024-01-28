<?php

namespace app\exception;


use Exception;
use Throwable;

class CustomException extends Exception
{
	public function __construct (string $message = "", int $code = 0, array $headers = [], Throwable $previous = NULL)
	{
		parent::__construct($message, $code, $headers, $previous);
	}

// 定义一个公共方法，将异常转换为字符串
	public function __toString ()
// 设置返回值类型为字符串
	: string
	{
		// 返回格式化的异常信息
		// 格式化字符串包括：
		// - 当前类名 static::class
		// - 异常信息 $this->message
		// - 发生异常的文件名 $this->file
		// - 发生异常的行号 $this->line
		// - 异常的堆栈跟踪信息 $this->getTraceAsString()
		return sprintf("Exception '%s' with message '%s' in %s:%s\nStack trace:\n%s", static::class, $this->message, $this->file, $this->line, $this->getTraceAsString());
	}


	public function getDetails ()
	: array
	{
		return [
			'class'     => static::class,
			'method'    => $this->getTrace()[0]['function'] ?? '',
			'line'      => $this->getLine(),
			'message'   => $this->getMessage(),
			'file'      => $this->getFile(),
			'trace'     => $this->getTrace(),
			'trace_str' => $this->getTraceAsString(),
		];
	}
}
