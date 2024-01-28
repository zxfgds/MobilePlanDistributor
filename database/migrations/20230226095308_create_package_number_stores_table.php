<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreatePackageNumberStoresTable extends AbstractMigration
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
        $table = $this->table('package_number_stores');
        $table->addColumn('name', 'string', ['null' => FALSE, 'default' => ''])
              ->addColumn('shop_id', 'integer', ['null' => FALSE, 'default' => 0])
              ->addColumn('status', 'integer', ['default' => 0, 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY])
              ->addColumn('message', 'string')
              ->save();
    }
}

