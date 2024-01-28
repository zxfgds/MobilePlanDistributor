<?php

namespace app\model;

use support\Model;

/**
 *
 */
class Banner extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = NULL;
    
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
    public $timestamps = FALSE;
    
    const TYPE_BANNER   = 0;
    const TYPE_POPUP    = 1;
    const TYPE_AD_INDEX = 2;
    const TYPES         = [
        self::TYPE_BANNER   => '幻灯片',
        self::TYPE_POPUP    => '弹窗广告',
        self::TYPE_AD_INDEX => '首页广告条',
    ];
}
