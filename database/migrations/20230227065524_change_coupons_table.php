<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class ChangeCouponsTable extends AbstractMigration
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
         * 修改优惠券  ,添加失效方式
         */
        
        $table = $this->table('coupons');
        $table->addColumn('expire_type', 'integer', ['default' => 0, 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'comment' => '失效方式, 0 不限制,1:领取后多少天失效, 2:固定失效日期'])
              ->addColumn('expired_value', 'string', ['null' => TRUE, 'comment' => '失效值 '])
              ->save();
    }
}
