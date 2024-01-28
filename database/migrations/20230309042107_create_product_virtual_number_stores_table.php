<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateProductVirtualNumberStoresTable extends AbstractMigration
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
        $table = $this->table('product_virtual_number_stores');
        $table->addColumn('name', 'string', ['null' => FALSE, 'comment' => '名称'])
              ->addColumn('shop_id', 'integer', ['null' => FALSE, 'comment' => '店铺'])
              ->addColumn('status', 'boolean', ['null' => FALSE, 'comment' => '状态; 0 等待审核, 1:审核中 2:审核失败  3:file'])
              ->save();
    }
}
