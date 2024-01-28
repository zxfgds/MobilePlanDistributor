<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class ChangePhoneNumbersTable extends AbstractMigration
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
        $this->table('package_numbers')->drop()->save();
        $table = $this->table('product_package_numbers');
        $table->addColumn('number', 'string', ['limit' => 11, 'comment' => '手机号码'])
              ->addColumn('operator', 'integer', ['limit' => 1, 'comment' => '运营商 1:移动 2:联通 3:电信'])
              ->addColumn('brand', 'string', ['limit' => 255, 'null' => TRUE, 'comment' => '品牌 名称'])
              ->addColumn('is_virtual', 'boolean', ['comment' => '是否虚拟号'])
              ->addColumn('shop_id', 'integer', ['default' => 0, 'comment' => '店铺ID'])
              ->addColumn('store_id', 'integer', ['default' => 0, 'comment' => '号码仓库ID'])
              ->addColumn('supplier_id', 'integer', ['default' => 0, 'comment' => '供货商ID'])
              ->addColumn('province', 'string', ['limit' => 255, 'null' => TRUE, 'comment' => '省份名称'])
              ->addColumn('city', 'string', ['limit' => 255, 'null' => TRUE, 'comment' => '城市  名称'])
              ->addColumn('province_code', 'string', ['limit' => 6, 'null' => TRUE, 'comment' => '省份编码'])
              ->addColumn('city_code', 'string', ['limit' => 6, 'null' => TRUE, 'comment' => '城市编码'])
              ->addColumn('status', 'integer', ['comment' => '状态'])
              ->addColumn('cost', 'float', ['comment' => '成本'])
              ->addColumn('price', 'float', ['comment' => '售价'])
              ->addColumn('price_show', 'float', ['comment' => '显示价格(原价)'])
              ->addColumn('price_hidden', 'boolean', ['comment' => '价格需要协商,开启后不显示价格, 需要跟客服沟通交易'])
              ->addColumn('price_floor', 'float', ['comment' => '底价'])
              ->addColumn('package', 'string', ['limit' => 255, 'null' => TRUE, 'comment' => '套餐信息'])
              ->addColumn('type', 'boolean', ['comment' => '是否虚拟号码'])
              ->addColumn('task_id', 'integer', ['comment' => '导入任务ID'])
              ->addColumn('commission_config', 'text', ['null' => TRUE, 'comment' => '佣金配置 (json格式)'])
              ->addColumn('remark', 'text', ['null' => TRUE, 'comment' => '备注'])
              ->save();
    }
}
