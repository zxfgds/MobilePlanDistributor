<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateShopBanlanceLogsTable extends AbstractMigration
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
         * 余额变动记录
         */
        $table = $this->table('shop_balance_logs');
        $table->addColumn('shop_id', 'integer', ['null' => FALSE])
              ->addColumn('amount', 'string', ['null' => FALSE, 'comment' => '变动金额 正数增加 负数减少'])
              ->addColumn('type', 'integer', ['null' => FALSE, 'comment' => '类型: 订单成交 , 提现,等', 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY])
              ->addColumn('info', 'json', ['null' => TRUE, 'comment' => '相关订单/提现信息'])
              ->addColumn('balance_before', 'string', ['comment' => '变动前余额'])
              ->addColumn('balance_after', 'string', ['comment' => '变动后余额'])
              ->save();
    }
}
