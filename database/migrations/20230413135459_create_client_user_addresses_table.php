<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateClientUserAddressesTable extends AbstractMigration
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
//		用户收件地址
		$table = $this->table('client_user_addresses');
		$table->addColumn('province', 'string', ['limit' => 50, 'comment' => '省份'])
		      ->addColumn('city', 'string', ['limit' => 50, 'comment' => '城市'])
		      ->addColumn('district', 'string', ['limit' => 50, 'comment' => '区县'])
		      ->addColumn('address', 'string', ['limit' => 255, 'comment' => '详细地址'])
		      ->addColumn('name', 'string', ['limit' => 50, 'comment' => '收件人姓名'])
		      ->addColumn('phone', 'string', ['limit' => 50, 'comment' => '收件人手机号'])
		      ->addColumn('is_default', 'boolean', ['default' => 0, 'comment' => '是否默认地址'])
		      ->addColumn('client_user_id', 'integer', ['comment' => '用户ID'])
		      ->save();
	}
}
