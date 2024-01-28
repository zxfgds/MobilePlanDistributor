<?php

namespace app\library;

use RuntimeException;
use Socket;

class RegexClient
{
    private string $socketPath;
    private Socket $socket;

    public function __construct(string $socketPath = '/tmp/pattern_matcher.sock')
    {
        $this->socketPath = $socketPath;
    }

//	public function __destruct ()
//	{
//		socket_close($this->socket);
//	}

    public function matchNumber(string $number): array|null
    {
        // 使用 trim() 清理 $number 变量
        $number = trim($number);

        $response = $this->sendRequest("MATCH_NUMBER {$number}");
        return json_decode($response, TRUE);
    }

    private function sendRequest(string $request) // 定义一个名为sendRequest的私有方法，参数类型为字符串类型
    : string                                       // 返回值类型为字符串类型
    {
        $this->socket = $this->createSocket(TRUE); // 创建一个TCP/IP套接字连接

        socket_write($this->socket, $request . "\n"); // 向套接字写入请求并添加一个换行符

        $response = ''; // 定义响应变量并初始化为空字符串
        $buf = ''; // 定义缓冲区变量并初始化为空字符串
        // 定义响应结束标记
        $eos = "\0";

        while (socket_recv($this->socket, $buf, 1024, 0) > 0) { // 循环接收套接字返回的数据
            $response .= $buf;                                  // 将读取的数据添加到响应变量中
            // 检查响应是否以预期的结束标记结尾
            if (str_ends_with($response, $eos)) {
                $response = str_replace($eos, '', $response);
                break;
            }
        }
        socket_close($this->socket); // 关闭套接字连接
        return $response;            // 返回响应结果
    }

    private function createSocket(bool $throwOnError = TRUE): ?Socket
    {
        $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);
        if (!$socket) {
            throw new RuntimeException('Unable to create socket');
        }
        if (!socket_connect($socket, $this->socketPath)) {
            throw new RuntimeException('Unable to connect to the Unix Socket');
        }

        // 设置读写超时
        $timeout = ["sec" => 5, "usec" => 0];
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, $timeout);
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, $timeout);

        return $socket;
    }

    public function restart(): string
    {
        return $this->sendRequest("RESTART");
    }

    public function list(): array
    {
        $response = $this->sendRequest("LIST");
        return json_decode($response, TRUE);
    }

    public function close(): void
    {
        socket_close($this->socket);
    }
}
