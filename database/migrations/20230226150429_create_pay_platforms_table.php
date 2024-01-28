<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreatePayPlatformsTable extends AbstractMigration
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
        /**
         * 支付账号
         */
        $table = $this->table('pay_gateways');
        $table->addColumn('name', 'string', ['null' => FALSE])
              ->addColumn('tested', 'boolean', ['default' => FALSE])
              ->addColumn('status', 'boolean', ['default' => FALSE])
              ->addColumn('config', 'json', ['null' => TRUE])
              ->addColumn('weight', 'integer', ['default' => 5, 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY])
              ->save();
    }
}
