<?php

namespace app\model;

use support\Model;

/**
 * @property integer $id (主键)
 * @property string $name 
 * @property integer $tested 
 * @property integer $status 
 * @property mixed $config 
 * @property integer $weight
 */
class PayGateway extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = null;

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
