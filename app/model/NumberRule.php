<?php

namespace app\model;

use support\Model;

/**
 *
 */
class NumberRule extends Model
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
    
    
    const RULES = [
        [
            'name'   => '顺子',
            'config' => [
                'AA'       => '',
                'AAA'      => '',
                'AAAA'     => '',
                'AAAAA'    => '',
                'AAAAAA'   => '',
                'AAAAAAA'  => '',
                'AAAAAAAA' => '',
                'AAAB'     => '',
                'AAAAB'    => '',
                'AAABBB'   => '',
                'AAABB'    => '',
                'AABBB'    => '',
            ],
        ],
    ];
}
