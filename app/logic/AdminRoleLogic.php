<?php

namespace app\logic;

use app\model\AdminRole;
use app\model\AdminRoleUser;

class AdminRoleLogic extends BaseLogic
{
    protected static string $model = AdminRole::class;
    
    public static function getRoles($uid): array
    {
        $model = new AdminRoleUser();
        
        return $model->where('user_id', $uid)->pluck('role_id')->toArray();
    }
}