<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateCustomersTable extends AbstractMigration
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
         * 客户表  下级 : 三方平台用户表  积分变动表  优惠券表
         */
        // username  password  phonenum integral balance from_client from_type from_id device_info
        $table = $this->table('customers');
        $table->addColumn('username', 'string', ['null' => TRUE, 'comment' => '用户名'])
              ->addColumn('password', 'string', ['null' => TRUE, 'comment' => '密码'])
              ->addColumn('phone_num', 'string', ['null' => TRUE, 'comment' => '手机号码'])
              ->addColumn('integral', 'integer', ['default' => 0, 'comment' => '积分'])
              ->addColumn('balance', 'string', ['default' => 0, 'comment' => '余额'])
              ->addColumn('from_client', 'integer', ['default' => 0, 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'comment' => '来源平台'])
              ->addColumn('from_type', 'integer', ['default' => 0, 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'comment' => '来源类型: 阅读/直播间等'])
              ->addColumn('from_id', 'integer', ['default' => 0, 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'comment' => '来源类型: 文章ID等'])
              ->addColumn('device_info', 'json', ['comment' => '设备信息'])
              ->save();
    }
}
