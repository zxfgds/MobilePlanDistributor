<?php

namespace app\controller\http;

use app\logic\TaskLogic;

class TaskController extends Controller
{
    protected string $logic = TaskLogic::class;
}