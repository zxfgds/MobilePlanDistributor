<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class ChangePhoneNumberPatternsTableV2 extends AbstractMigration
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
        $table = $this->table('phone_number_patterns');
        $table->addColumn('status', 'boolean', ['default' => TRUE, 'comment' => '状态,关闭后全局关闭'])
              ->addColumn('select_num_status', 'boolean', ['default' => TRUE, 'comment' => '选号页面是否显示'])
              ->save();
    }
}
