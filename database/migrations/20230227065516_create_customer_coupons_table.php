<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateCustomerCouponsTable extends AbstractMigration
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
         * 客户优惠券表
         */
        $table = $this->table('customer_coupons');
        $table->addColumn('customer_id', 'integer', ['null' => FALSE, 'comment' => '客户'])
              ->addColumn('coupon_id', 'integer', ['null' => FALSE, 'comment' => '优惠券表'])
              ->addColumn('expired_at', 'datetime', ['null' => TRUE, 'comment' => '失效日期'])
              ->addColumn('status', 'integer', ['null' => FALSE, 'comment' => '状态', 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 0])
              ->save();
    }
}
