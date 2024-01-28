<?php

namespace app\controller\admin;

use app\library\Arr;
use app\library\Hash;
use app\library\Validate;
use app\logic\AdminMenuLogic;
use app\logic\AdminUserLogic;
use support\Response;

class AdminUserController extends Controller
{
    protected string $logic = AdminUserLogic::class;
    
    public function login(): Response
    {
        $data = $this->params();
        if ($errors = Validate::check($data, [
            'username' => ['required' => TRUE, 'min' => 5],
            'password' => ['required' => TRUE, 'min' => 5],
        ])) {
            return $this->error($errors);
        }
        
        $user = call_user_func([$this->logic, 'getOne'], ['username' => $data['username']]);
        if (empty($user)) return $this->error('用户或者密码错误');
        if (!Hash::check($data['password'], $user['password'])) return $this->error('用户名或者密码错误');
        $token = call_user_func([$this->logic, 'createToken'], $user['id']);
        return $this->success(['token' => $token]);
    }
    
    public function info(): Response
    {
        $uid = call_user_func([$this->logic, 'validToken']);
        if (empty($uid)) return $this->error('请登陆', 401);
        $user = call_user_func([$this->logic, 'getOne'], $uid);
        return $user ? $this->success(Arr::except($user, 'password')) : $this->error('请登陆', 401);
    }
    
    public function menus(): Response
    {
        $user  = call_user_func([$this->logic, 'user']);
        $menus = AdminMenuLogic::menus($user['id'], TRUE, $user['is_super']);
        return $this->success(['list' => $menus]);
    }
}