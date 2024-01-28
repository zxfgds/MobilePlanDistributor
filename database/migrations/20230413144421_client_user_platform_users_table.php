<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class ClientUserPlatformUsersTable extends AbstractMigration
{
	/**
	 * Change Method.
	 *
	 * Write your reversible migrations using this method.
	 *
	 * More information on writing migrations is available here:
	 * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
	 *
	 * Remember to call "create()" or "update()" and NOT "save()" when working
	 * with the Table class.
	 */
	public function change(): void
	{
		$table = $this->table('client_user_platform_users');
		$table->addColumn('open_id', 'string', ['null' => FALSE, 'limit' => 255, 'comment' => '平台openid'])
		      ->addColumn('platform_id', 'integer', ['null' => FALSE, 'limit' => 11, 'comment' => '平台类型'])
		      ->addColumn('client_user_id', 'integer', ['null' => FALSE, 'comment' => '客户用户id'])
		      ->addColumn('avatar', 'string', ['null' => TRUE, 'limit' => 255, 'comment' => '头像'])
		      ->addColumn('nickname', 'string', ['null' => TRUE, 'limit' => 255, 'comment' => '昵称'])
		      ->save();
		
	}
}
