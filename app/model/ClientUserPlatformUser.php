<?php

namespace app\model;

use support\Model;

/**
 *
 */
class ClientUserPlatformUser extends Model
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = "client_user_platform_users";
	
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
	
	
}
