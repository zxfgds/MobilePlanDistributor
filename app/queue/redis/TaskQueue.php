<?php

namespace app\queue\redis;

use app\logic\TaskLogic;
use Webman\RedisQueue\Consumer;

class TaskQueue implements Consumer
{
    public string $queue = 'task';
    
    /**
     * @param $data
     *
     * @return void
     */
    public function consume($data): void
    {
        $taskId = $data;
        try {
            $task = TaskLogic::getOne($taskId);

        } catch (\Exception $e) {
            return;
        }
    }
}