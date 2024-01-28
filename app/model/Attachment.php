<?php

namespace app\model;

use support\Model;

/**
 * @property integer $id (主键)
 * @property string $url 
 * @property string $filename 
 * @property string $path 
 * @property integer $is_hidden 
 * @property string $location
 */
class Attachment extends Model
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
