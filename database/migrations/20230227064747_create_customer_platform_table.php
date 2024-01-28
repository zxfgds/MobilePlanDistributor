<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateCustomerPlatformTable extends AbstractMigration
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
         * 客户第三方平台关联表
         */
        // customer_id platform open_id union_id avatar nickname  other
        $table = $this->table('customer_platform');
        $table->addColumn('customer_id', 'integer', ['null' => FALSE, 'comment' => '客户ID'])
              ->addColumn('platform', 'string', ['null' => FALSE, 'comment' => '平台'])
              ->addColumn('open_id', 'integer', ['null' => FALSE, 'comment' => '三方平台用户ID'])
              ->addColumn('union_id', 'integer', ['null' => TRUE, 'comment' => '三方平台联合ID'])
              ->addColumn('nickname', 'string', ['null' => TRUE, 'comment' => '昵称'])
              ->addColumn('avatar', 'string', ['null' => TRUE, 'comment' => '头像'])
              ->addColumn('other', 'json', ['null' => TRUE, 'comment' => '其他信息'])
              ->save();
    }
}
