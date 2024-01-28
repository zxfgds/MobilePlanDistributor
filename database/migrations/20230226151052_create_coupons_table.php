<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateCouponsTable extends AbstractMigration
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
        $table = $this->table('coupons');
        // 名称 / 有效期 / 指定产品 /金额 /获取方式
        $table->addColumn('name', 'string', ['null' => FALSE, 'comment' => '名称'])
              ->addColumn('amount', 'string', ['null' => FALSE, 'comment' => '面值'])
              ->addColumn('integral', 'integer', ['default' => 0, 'comment' => '积分金额'])
              ->addColumn('get_type', 'integer', ['default' => 0, 'comment' => '获取方式 :0 登录发放,注册发放,后台发放,购买发放'])
              ->addColumn('product_type', 'integer', ['null' => FALSE, 'comment' => '适用产品类型'])
              ->save();
    }
}
