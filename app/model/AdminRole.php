<?php

namespace app\model;

use support\Model;

/**
 * @property integer $id (主键)
 * @property string $name 
 * @property integer $parent_id 
 * @property string $intro 
 * @property integer $is_super
 */
class AdminRole extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_roles';

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
