<?php

namespace app\model;

use support\Model;

/**
 * @property integer $id (主键)
 * @property integer $type 类型
 * @property string $receiver_province 收件地址:省份
 * @property string $receiver_city 收件地址:城市
 * @property string $receiver_area 收件地址:城市
 * @property string $receiver_address 收件地址
 * @property string $receiver_name 
 * @property string $receiver_phone_num 
 * @property string $card_id 
 * @property string $remark
 */
class BlackList extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'black_list';

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
