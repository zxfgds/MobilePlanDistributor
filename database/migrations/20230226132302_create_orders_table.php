<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateOrdersTable extends AbstractMigration
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
        $table = $this->table('orders');
        $table->addColumn('order_no', 'string', ['null' => FALSE])
              ->addColumn('out_trade_no', 'string', ['null' => TRUE, 'comment' => '外部订单号'])
              ->addColumn('operator_trade_no', 'string', ['null' => TRUE, 'comment' => '运营商订单号'])
              ->addColumn('product_type', 'integer', ['null' => FALSE, 'comment' => '订单类型', 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'signed' => FALSE])
              ->addColumn('shop_type', 'integer', ['null' => FALSE, 'default' => 0, 'comment' => '来源: 0:本系统商城,抖店,快店,红店'])
              ->addColumn('shop_id', 'integer', ['null' => FALSE, 'default' => 0, 'comment' => '店铺ID'])
              ->addColumn('channel_id', 'integer', ['null' => TRUE, 'default' => 0, 'comment' => '渠道ID'])
              ->addColumn('customer_id', 'integer', ['null' => FALSE, 'default' => 0, 'comment' => '客户ID'])
              ->addColumn('platform_id', 'integer', ['null' => TRUE, 'comment' => '平台'])
              ->addColumn('from', 'integer', ['null' => TRUE, 'comment' => '来源', 'default' => 0])
              ->addColumn('from_id', 'integer', ['null' => TRUE, 'comment' => '来源ID', 'default' => 0])
//            主状态  新订单  已支付
              ->addColumn('status', 'integer', ['null' => FALSE, 'default' => 0, 'comment' => '主状态 :新订单,已支付,已完成,失败'])
//            校验状态码
              ->addColumn('intercept_status', 'integer', ['null' => TRUE, 'default' => 0, 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'comment' => '拦截状态'])
              ->addColumn('intercept_info', 'json', ['null' => TRUE,]) // 'default' => '{"type":"","message":""}'
              ->addColumn('amount', 'string', ['default' => 0, 'comment' => '价格'])
              ->addColumn('product_id', 'integer', ['default' => 0, 'comment' => '产品ID'])
              ->addColumn('phone_num', 'string', ['null' => TRUE, 'comment' => '手机号码'])
              ->addColumn('express_status', 'integer', ['default' => 0, 'comment' => '物流状态', 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY])
              ->addColumn('express_data', 'json', ['null' => TRUE, 'comment' => '物流信息'])
//            分销
              ->addColumn('settle_status', 'boolean', ['null' => TRUE, 'default' => FALSE, 'comment' => '分润状态'])
//            同步
              ->addColumn('operator_sync_status', 'boolean', ['null' => TRUE, 'default' => FALSE, 'comment' => '同步状态'])
              ->save();
    }
}
