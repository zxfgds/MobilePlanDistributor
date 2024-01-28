<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateProductVirtualNumbersTable extends AbstractMigration
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
        $table = $this->table('product_virtual_numbers');
        $table->addColumn('number', 'string', ['null' => FALSE, 'comment' => '号码'])
              ->addColumn('operator', 'string')
              ->addColumn('type', 'string')
              ->addColumn('province', 'string')
              ->addColumn('city', 'string')
              ->addColumn('status', 'integer', ['signed' => FALSE, 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 0])
              ->addColumn('cost', 'integer', ['signed' => FALSE, 'default' => 0, 'comment' => '成本'])
              ->addColumn('price', 'integer', ['default' => 0, 'signed' => FALSE])
              ->addColumn('price_show', 'integer', ['default' => 0, 'signed' => FALSE])
              ->addColumn('package', 'text', ['null' => TRUE, 'comment' => '套餐信息'])
              ->addColumn('commission_1', 'integer', ['default' => 0, 'signed' => FALSE])
              ->addColumn('commission_2', 'integer', ['default' => 0, 'signed' => FALSE])
              ->addColumn('remark', 'string', ['null' => TRUE])
              ->addColumn('store_id', 'integer', ['signed' => FALSE, 'default' => 0, 'comment' => '库ID'])
              ->addColumn('shop_id', 'integer', ['signed' => FALSE, 'default' => 0])
              ->addColumn('supplier_id', 'integer', ['signed' => FALSE, 'default' => 0, 'null' => TRUE])
              ->save();
    }
}
