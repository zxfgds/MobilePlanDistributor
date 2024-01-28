<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class ModifyOperatorApisTable extends AbstractMigration
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
        $table = $this->table('operator_apis');
        $table->addColumn('req_sleep_micros', 'integer', ['default' => 0, 'limit' => 11, 'comment' => '请求间隔'])
              ->addColumn('concurrent_count', 'integer', ['default' => 1, 'limit' => 5, 'comment' => '并发'])
              ->save();
    }
}
