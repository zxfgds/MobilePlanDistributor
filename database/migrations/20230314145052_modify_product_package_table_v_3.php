<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class ModifyProductPackageTableV3 extends AbstractMigration
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
        $table = $this->table('product_packages');
        $table->addColumn('sales', 'integer', ['null' => TRUE, 'default' => 0, 'comment' => '销量'])
              ->addColumn('standard_fee', 'integer', ['default' => 0, 'comment' => '规格->月租'])
              ->addColumn('standard_voice', 'integer', ['default' => 0, 'comment' => '规格->通话时长'])
              ->addColumn('standard_sms', 'integer', ['default' => 0, 'comment' => '规格->短信数量'])
              ->addColumn('standard_data', 'string', ['default' => 0, 'comment' => '规格->流量'])
              ->addColumn('region_province_code', 'string', ['default' => 0, 'comment' => "归属省份编码"])
              ->addColumn('region_province_name', 'string', ['default' => 0, 'comment' => "归属省份编码"])
              ->addColumn('region_city_code', 'string', ['default' => 0, 'comment' => "归属城市编码"])
              ->addColumn('region_city_name', 'string', ['default' => 0, 'comment' => "归属城市编码"])
              ->save();
        
    }
}
