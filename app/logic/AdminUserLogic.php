<?php

namespace app\logic;

use app\library\AuthToken;
use app\model\AdminUser;

class AdminUserLogic extends BaseLogic
{
    protected static string $model = AdminUser::class;
    
    protected static bool $useCache = true;
    private static string $userType = "admin";
    
    /**
     * @param $userId
     *
     * @return string
     */
    public static function createToken($userId): string
    {
        return AuthToken::generateToken($userId . "|" . static::$userType);
    }
    
    public static function user()
    {
        $uid = static::validToken();
        return static::getOne($uid);
    }
    
    public static function getOne(int|array $idOrCondition): array
    {
        $detail = parent::getOne($idOrCondition);
        
        if (empty($detail)) return [];
        // 获取用户的角色
        
        $detail['roles'] = AdminRoleLogic::getRoles($detail['id']);
        
        return $detail;
    }
    
    /**
     * @param null $token
     *
     * @return array|bool|string|int
     */
    public static function validToken($token = NULL): array|bool|string|int
    {
        $token = $token ?? _token();
        if (empty($token)) return FALSE;
        $data = AuthToken::validateToken($token);
        if (empty($data)) return FALSE;
        $array    = explode('|', $data);
        $userId   = $array[0] ?? 0;
        $userType = $array[1] ?? NULL;
        if ($userType != static::$userType) return FALSE;
        return $userId ?? FALSE;
    }
}