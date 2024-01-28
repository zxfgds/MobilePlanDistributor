<?php

namespace app\controller\http;

use app\logic\ClientUserLogic;

class ClientUserController extends Controller
{
    protected string $logic = ClientUserLogic::class;
}