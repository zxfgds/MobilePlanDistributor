<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateShopsTable extends AbstractMigration
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
        $table = $this->table('shops');
//        店铺配置
        $table->addColumn('config', 'json', ['comment' => '配置', 'null' => TRUE])
            //        用户信息
              ->addColumn('username', 'string', ['null' => TRUE, 'comment' => '用户名'])
              ->addColumn('password', 'string', ['null' => TRUE, 'comment' => '密码'])
              ->addColumn('phone_num', 'string', ['null' => TRUE])
              ->addColumn('wechat_open_id', 'string', ['null' => TRUE])
              ->addColumn('wechat_followed', 'boolean', ['default' => FALSE])
              ->addColumn('parent_id', 'integer', ['default' => 0])
              ->addColumn('balance', 'string', ['default' => 0, 'comment' => '余额'])
              ->addColumn('balance_locked', 'string', ['default' => 0, 'comment' => '锁定余额'])
              ->addColumn('alipay_info', 'json', ['null' => TRUE])
              ->addColumn('bank_info', 'json', ['null' => TRUE])
              ->save();
    }
}
