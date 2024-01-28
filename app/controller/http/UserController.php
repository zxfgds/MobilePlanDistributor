<?php

namespace app\controller\http;

use app\library\Validate;
use app\logic\ClientUserLogic;
use app\logic\PlatformLogic;
use Exception;
use RedisException;
use support\Response;

class UserController extends Controller
{

    protected string $logic = ClientUserLogic::class;

    /**
     * 用户登录
     *
     * @throws Exception
     */
    public function loginByPassword(): Response
    {
        $data = $this->params();
        $errors = Validate::check($data, [
            ['phone' => 'required',],
            ['password' => 'required',],
        ]);
        if (!empty($errors)) {
            return $this->error('缺少必要参数');
        }
        try {
            $token = ClientUserLogic::login('password', $data);
            return $this->success(['token' => $token]);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function h5SilentLogin()
    {
        // 获取参数
        $data = $this->params();
        // 验证参数是否合法
        $errors = Validate::check($data, [
            ['code' => 'required'],
        ]);

        // 如果参数不合法，则返回错误信息
        if ($errors) {
            return $this->error('缺少必要参数CODE');
        }

        try {
            // 通过 CODE 获取用户信息
            $res = PlatformLogic::codeToUser($data['code']);
            // 使用 openid 登录，获取 token
            $token = ClientUserLogic::login('openid', ['openid' => $res['openid']]);
            // 返回成功信息，包含 token
            return $this->success(['token' => $token]);
        } catch (Exception $e) {
            // 如果出现异常，则返回错误信息
            return $this->error($e->getMessage());
        }
    }

    /**
     * openId 静默登录
     * @return Response
     */
    public function miniSilentLogin(): Response
    {
        // 获取参数
        $data = $this->params();
        // 验证参数是否合法
        $errors = Validate::check($data, [
            ['code' => 'required'],
        ]);

        // 如果参数不合法，则返回错误信息
        if ($errors) {
            return $this->error('缺少必要参数CODE');
        }

        try {
            // 通过 CODE 获取用户信息
            $res = PlatformLogic::codeToUser($data['code']);
            // 使用 openid 登录，获取 token
            $token = ClientUserLogic::login('openid', ['openid' => $res['openid']]);
            // 返回成功信息，包含 token
            return $this->success(['token' => $token]);
        } catch (Exception $e) {
            // 如果出现异常，则返回错误信息
            return $this->error($e->getMessage());
        }
    }


    /**
     * 社交账号登录
     *
     */
    public function socialLogin()
    {
        $data = $this->params();

        $errors = Validate::check($data, [
            ['code' => 'required',],
        ]);

        if ($errors) return $this->error('缺少必要参数CODE');

        try {
            $res = PlatformLogic::codeToUser($data['code']);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * 手机号登录
     *
     * @return Response
     * @throws Exception
     */
    public function loginByPhoneCode(): Response
    {
        $data = $this->params();
        $errors = Validate::check($data, [
            ['phone' => 'required',],
            ['code' => 'required',],
        ]);
        if (!empty($errors)) return $this->error('缺少必要参数');

        $token = ClientUserLogic::login('phone', $data);
        return $this->success(['token' => $token]);
    }

    /**
     * @return Response
     * @throws RedisException
     */
    public function getAuthUrl(): Response
    {
        $data = $this->params();
        $url = PlatformLogic::getAuthUrl($data['url'] ?? null);
        return $this->success(['url' => $url]);
    }

}