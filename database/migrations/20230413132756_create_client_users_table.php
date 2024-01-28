<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateClientUsersTable extends AbstractMigration
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
		$table = $this->table('client_users');
		$table->addColumn('phone_number', 'string', ['null' => TRUE, 'default' => NULL, 'limit' => 50, 'comment' => '手机号'])
		      ->addColumn('password', 'string', ['null' => TRUE, 'default' => NULL, 'limit' => 255, 'comment' => '密码'])
		      ->addColumn('nickname', 'string', ['null' => TRUE, 'default' => NULL, 'limit' => 50, 'comment' => '昵称'])
		      ->addColumn('avatar', 'string', ['null' => TRUE, 'default' => NULL, 'limit' => 255, 'comment' => '头像'])
		      ->addColumn('status', 'integer', ['default' => 1, 'limit' => Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'comment' => '状态 1正常 2禁用'])
		      ->addColumn('reg_ip', 'string', ['default' => NULL, 'limit' => 50, 'comment' => '注册IP'])
		      ->addColumn('reg_time', 'integer', ['default' => 0, 'limit' => 10, 'comment' => '注册时间'])
		      ->addColumn('last_login_ip', 'string', ['default' => NULL, 'limit' => 50, 'comment' => '最后登录IP'])
		      ->addColumn('last_login_time', 'integer', ['default' => 0, 'limit' => 10, 'comment' => '最后登录时间'])
		      ->addColumn('balance', 'decimal', ['default' => 0, 'precision' => 10, 'scale' => 2, 'comment' => '余额'])
		      ->addColumn('credit', 'integer', ['default' => 0, 'comment' => '积分'])
		      ->addColumn('reg_platform_id', 'integer', ['default' => 0, 'limit' => 10, 'comment' => '注册平台ID'])
		      ->addColumn('reg_from_type', 'string', ['default' => NULL, 'limit' => 10, 'comment' => '注册来源类型'])
		      ->addColumn('reg_from_id', 'integer', ['default' => 0, 'limit' => 10, 'comment' => '注册来源ID'])
		      ->addColumn('reg_shop_id', 'integer', ['default' => 0, 'limit' => 10, 'comment' => '注册店铺ID'])
		      ->addColumn('reg_channel_id', 'integer', ['default' => 0, 'limit' => 10, 'comment' => '注册渠道ID'])
		      ->addIndex(['phone_number'], ['unique' => TRUE])
		      ->addIndex(['reg_platform_id'], ['unique' => TRUE])
		      ->create();
	}
}
