<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateClientConfigsTable extends AbstractMigration
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
	public function change ()
	: void
	{
		$table = $this->table('client_configs');
		$table->addColumn('shop_id', 'integer', ['limit' => 11])
		      ->addColumn('channel_id', 'integer', ['limit' => 11, 'default' => 0])
		      ->addColumn('config', 'json')
		      ->save();
	}
}
