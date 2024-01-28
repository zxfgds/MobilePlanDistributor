<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateBlackListTable extends AbstractMigration
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
         * 黑名单表
         * 三个类型: 1. 收件地址 (省市区 街道)  2 .收件人 (姓名/电话)  3.开卡人: 证件好
         */
        $table = $this->table('black_list');
        $table->addColumn('type', 'integer', ['null' => FALSE, 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'comment' => '类型'])
              ->addColumn('receiver_province', 'string', ['null' => TRUE, 'comment' => '收件地址:省份'])
              ->addColumn('receiver_city', 'string', ['null' => TRUE, 'comment' => '收件地址:城市'])
              ->addColumn('receiver_area', 'string', ['null' => TRUE, 'comment' => '收件地址:城市'])
              ->addColumn('receiver_address', 'string', ['null' => TRUE, 'comment' => '收件地址'])
              ->addColumn('receiver_name', 'string', ['null' => TRUE])
              ->addColumn('receiver_phone_num', 'string', ['null' => TRUE])
              ->addColumn('card_id', 'string', ['null' => TRUE])
              ->addColumn('remark', 'string', ['null' => TRUE])
              ->save();
    }
}
