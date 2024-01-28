<?php

namespace app\logic;

use app\exception\CustomException;
use app\library\Hash;
use App\Library\SmsManager;
use app\library\UserToken;
use app\model\ClientUser;
use app\model\Platform;
use Exception;
use Illuminate\Support\Str;
use RedisException;

class ClientUserLogic extends BaseLogic
{
    protected static string $model = ClientUser::class;

    protected static string $userType = 'client';

    /**
     * 创建用户
     *
     * @param array $data 用户信息
     *
     * @return bool|int 成功返回用户ID，失败返回false
     * @throws CustomException
     */
    public static function create($data): bool|int
    {
        // 获取请求查询数据
        $queryData = getRequestQuery();

        // 如果密码不存在，设置默认密码为"123456"
        $data['password'] = bcrypt($data['password'] ?? "123456");

        // 获取平台ID，并设置注册平台ID
        $data['reg_platform_id'] = PlatformLogic::getPlatformId();

        // 设置注册来源类型，默认为0
        $data['reg_from_type'] = $queryData['form'] ?? 0;

        // 设置注册来源ID，默认为0
        $data['reg_from_id'] = $queryData['from_id'] ?? 0;

        // 设置注册店铺ID，默认为0
        $data['reg_shop_id'] = $queryData['shop_id'] ?? 0;

        // 设置注册渠道ID，默认为0
        $data['reg_channel_id'] = $queryData['channel_id'] ?? 0;

        // 设置注册IP地址
        $data['reg_ip'] = getClientIp();

        // 设置注册时间戳
        $data['reg_time'] = time();

        // 调用父类create方法，完成用户创建操作
        return parent::create($data);
    }


    /**
     * 根据不同的登录类型进行登录，返回 token
     *
     * @param string $type 登录类型 ('password', 'phone', 'openid')
     * @param array $data 登录所需的数据
     *
     * @return string 登录成功后返回 token
     * @throws Exception 登录类型错误时抛出异常
     *
     */
    public static function login(string $type, array $data): string
    {
        //通过匹配不同的登录类型调用相应的方法进行登录，返回token。
        $token = match ($type) {
            'password' => static::loginByPassword($data['phone_number'], $data['password']), //使用手机号和密码进行登录。
            'phone' => static::loginByPhone($data['phone_number'], $data['sms_code']), //使用手机号和短信验证码进行登录。
            'openid' => static::loginByOpenId($data['openid']), //使用openid进行登录。
            default => throw new Exception("登录类型错误"), //登录类型错误抛出异常。
        };

        $userId = UserToken::validateToken(static::$userType, $token); //验证token并获取用户id。

        if ($userId) {
            $user = static::getOne($userId);
            if ($user['status'] == 0) throw new Exception("账号已被禁用");
        }

        static::modify($userId, [
            'last_login_time' => time(), //记录最后登录时间。
            'last_login_ip' => getClientIp(), //记录最后登录IP地址。
        ]);

        return $token; //返回token。
    }


    /**
     * @param $phoneNumber
     * @param $password
     *
     * @return string
     * @throws RedisException
     * @throws Exception
     */
    public
    static function loginByPassword($phoneNumber, $password): string
    {

        $user = static::getOne(['phone_number' => $phoneNumber]);
        if (empty($user)) throw new Exception("用户不存在");
        Hash::check($password, $user['password']) ?: throw new Exception("密码错误");
        return UserToken::generateToken($user['id'], 'client');
    }

    /**
     * 验证码登录
     *
     * @param $phone
     * @param $smsCode
     *
     * @return string
     * @throws RedisException
     * @throws Exception
     */
    public
    static function loginByPhone($phone, $smsCode): string
    {
        $valid = SmsManager::verifyCode($phone, $smsCode);
        if (!$valid) throw new Exception("验证码错误");
        $user = static::getOne(['phone_number' => $phone]);
        if (!empty($user)) return UserToken::generateToken($user['id'], static::$userType);
        $userId = static::create(['phone_number' => $phone, 'nickname' => static::createNickName()]);
        return UserToken::generateToken($userId, static::$userType);
    }

    /**
     * 使用用户的 OpenID 进行登录。
     *
     * @param string $openId 要登录的用户的 OpenID
     *
     * @return array|null 返回用户信息数组，如果用户不存在则返回 null
     * @throws Exception 当创建用户或平台用户失败时抛出异常
     */
    public
    static function loginByOpenId(string $openId): ?string
    {
        // 获取平台用户信息
        $platformUser = ClientUserPlatformUserLogic::getDetailByOpenId($openId);

        // 如果平台用户不存在
        if (empty($platformUser)) {
            // 检查当前用户是否已经登录
            $userId = UserToken::validateToken();
            if (empty($userId)) {
                // 创建新用户
                $userId = static::create(['nickname' => static::createNickName()]);
                // 为新用户创建平台用户
                ClientUserPlatformUserLogic::create(['open_id' => $openId, 'client_user_id' => $userId]);
            }
        } else {
            // 获取用户信息
            $userId = $platformUser['client_user_id'];
            $user = static::getOne($userId);
            // 如果 openId 存在但用户不存在
            if (empty($user)) {
                // 处理这种情况，例如创建新用户并将其与平台用户关联
                // 创建新用户
                $userId = static::create(['nickname' => static::createNickName()]);
                // 更新平台用户的关联
                ClientUserPlatformUserLogic::modify(['open_id' => $openId], ['client_user_id' => $userId]);
            }
        }
        return UserToken::generateToken($userId, 'client');
    }

    /**
     * 用户创建订单的时候 直接登录
     *
     * @param $phoneNumber
     *
     * @return string|true
     * @throws RedisException
     * @throws CustomException
     * @throws Exception
     */
    public
    static function createOrBindPhoneNumber($phoneNumber): bool|string
    {
        //todo: 用户下单时候调用，如果用户已经登录，直接绑定手机号码，如果用户未登录，创建新用户并绑定手机号码
        // 验证用户Token
        $userId = UserToken::validateToken(static::$userType);

        if (!empty($userId)) { // 如果已登录，尝试绑定手机号码
            $user = ClientUserLogic::getOne($userId);

            if (!empty($user) && empty($user['phone_number'])) { // 判断是否存在手机号码
                // 修改手机号码
                static::modify($userId, ['phone_number' => $phoneNumber]);
            }

            return TRUE;
        }

        // 如果不存在手机号码，创建新用户并生成Token
        $user = static::getOne(['phone_number' => $phoneNumber]);

        if (empty($user)) { // 如果手机号码不存在，创建新用户
            $userId = static::create([
                'phone_number' => $phoneNumber,
                'nickname' => static::createNickName(),
            ]);
        }

        return UserToken::generateToken($userId, static::$userType); // 生成Token
    }


    /**
     * 创建一个随机昵称
     *
     * @return string 返回一个字符串类型的昵称
     */
    public
    static function createNickName(): string
    {
        // 获取当前平台的 ID
        $platformId = PlatformLogic::getPlatformId();

        // 根据获取到的平台 ID 获取对应的平台名称，如果没有则使用默认值 "H5"
        $platformName = Platform::PLATFORM_NAMES[$platformId] ?? "H5";

        // 返回组合好的字符串，以平台名称开始，然后是 "用户"，最后是一个 8 位长度的随机字符串
        return $platformName . "用户" . Str::random(8);
    }

}