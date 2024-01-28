<?php

namespace app\model;

use support\Model;

/**
 *
 */
class ProductPackage extends Model
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
    
    const ON_SALE         = 0;
    const OFF_SALE        = 1;
    const TO_SALE         = 2;
    const SOLD            = 3;
    const PENDING_REVIEW  = 4;
    const FORCED_OFF_SALE = 5;
    
    private static array $productStatus = array(
        self::ON_SALE         => '在售',
        self::OFF_SALE        => '下架',
        self::SOLD            => '售出',
        self::TO_SALE         => '申请上架',
        self::PENDING_REVIEW  => '审核',
        self::FORCED_OFF_SALE => '强制下架',
    );
    
}
