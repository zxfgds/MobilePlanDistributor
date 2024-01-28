<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateClientUserCreditLogsTable extends AbstractMigration
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
		// 用户积分变动记录
		$table = $this->table('client_user_credit_logs');
		$table->addColumn('client_user_id', 'integer', ['comment' => '用户ID'])
		      ->addColumn('type', 'integer', ['comment' => '变动类型'])
		      ->addColumn('amount', 'integer', ['comment' => '变动积分'])
		      ->addColumn('credit', 'integer', ['comment' => '变动后积分'])
		      ->addColumn('remark', 'string', ['limit' => 255, 'comment' => '备注'])
		      ->save();
	}
}
