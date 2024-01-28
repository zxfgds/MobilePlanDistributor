<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class ModifyVirtualNumbersTable extends AbstractMigration
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
        $table->addColumn('province_code', 'string', ['null' => TRUE, 'comment' => '省份编码'])
              ->addColumn('city_code', 'string', ['null' => FALSE, 'comment' => '城市编码'])
              ->addColumn('price_floor', 'string', ['null' => TRUE, 'default' => 0, 'comment' => '销售底价'])
              ->addColumn('commission_config', 'json', ['comment' => '佣金配置'])
              ->addColumn('import_id', 'integer', ['comment' => '导入id', 'default' => 0])
              ->removeColumn('commission_1')
              ->removeColumn('commission_2')
              ->addIndex('type')
              ->addIndex('number', ['unique' => TRUE])
              ->addIndex('status')
              ->save();
    }
}
