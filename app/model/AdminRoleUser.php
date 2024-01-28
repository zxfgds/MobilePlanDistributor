<?php

namespace app\model;

use support\Model;

/**
 * @property integer $id (主键)
 * @property integer $user_id 
 * @property integer $role_id
 */
class AdminRoleUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_role_user';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    
}
