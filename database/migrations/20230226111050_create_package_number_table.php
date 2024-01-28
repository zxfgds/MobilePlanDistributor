<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreatePackageNumberTable extends AbstractMigration
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
         * 套餐选号库
         */
        
        $table = $this->table('package_numbers');
        $table->addColumn('number', 'string')
              ->addColumn('type', 'string', ['null' => TRUE, 'comment' => '号码规律'])
              ->addColumn('province', 'string', ['null' => TRUE, 'comment' => '省份'])
              ->addColumn('city', 'string', ['null' => TRUE, 'comment' => '城市'])
              ->addColumn('package_id', 'integer', ['null' => TRUE, 'default' => 0, 'comment' => '绑定套餐'])
              ->addColumn('store_id', 'integer', ['signed' => FALSE, 'default' => 0, 'comment' => '库ID'])
              ->addColumn('shop_id', 'integer', ['signed' => FALSE, 'default' => 0])
              ->save();
    }
}
