<?php

namespace app\model;

use support\Model;

/**
 * @property integer $id (主键)
 * @property string $number 
 * @property string $operator 
 * @property string $type 
 * @property string $province 
 * @property string $city 
 * @property integer $status 
 * @property integer $cost 
 * @property integer $price 
 * @property integer $price_show 
 * @property string $package 
 * @property integer $commission_1 
 * @property integer $commission_2 
 * @property string $remark 
 * @property integer $store_id 库ID
 * @property integer $shop_id 
 * @property integer $supplier_id
 */
class ProductVirtualNumber extends Model
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
