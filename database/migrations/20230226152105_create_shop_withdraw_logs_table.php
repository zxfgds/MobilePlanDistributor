<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateShopWithdrawLogsTable extends AbstractMigration
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
         * 提现记录
         */
        $table = $this->table('shop_withdraw_logs');
        $table->addColumn('shop_id', 'integer', ['null' => FALSE, 'comment' => '店铺ID'])
              ->addColumn('amount', 'string', ['null' => FALSE, 'comment' => '提现金额'])
              ->addColumn('status', 'integer', ['default' => 0, 'comment' => '状态: 0:等待审核 ,1 成功 2:拒绝,3:取消'])
              ->addColumn('proof', 'string', ['null' => TRUE, 'comment' => '转账证明'])
              ->addColumn('message', 'string', ['null' => TRUE, "comment" => '审核消息'])
              ->addColumn('receive_info', 'json', ['null' => TRUE, 'comment' => '收款账号信息']) // 'default' => '{"type":"alipay","account":"","name":""}',
              ->save();
    }
}
