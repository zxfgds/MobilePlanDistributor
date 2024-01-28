<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateClientUserBalanceLogsTable extends AbstractMigration
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
		// 用户余额变动记录
		$table = $this->table('client_user_balance_logs');
		$table->addColumn('client_user_id', 'integer', ['comment' => '用户ID'])
		      ->addColumn('type', 'integer', ['comment' => '变动类型'])
		      ->addColumn('amount', 'decimal', ['precision' => 10, 'scale' => 2, 'comment' => '变动金额'])
		      ->addColumn('balance', 'decimal', ['precision' => 10, 'scale' => 2, 'comment' => '变动后余额'])
		      ->addColumn('remark', 'string', ['limit' => 255, 'comment' => '备注'])
		      ->save();
	}
}
