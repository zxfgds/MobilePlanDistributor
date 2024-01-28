<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateIntegralLogsTable extends AbstractMigration
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
        $table = $this->table('integral_logs');
        $table->addColumn('customer_id', 'integer', ['null' => FALSE, 'comment' => '会员ID'])
              ->addColumn('amount', 'integer', ['null' => FALSE, 'comment' => '变动金额'])
              ->addColumn('intro', 'string', ['null' => FALSE, 'comment' => '变动说明'])
              ->save();
    }
}
