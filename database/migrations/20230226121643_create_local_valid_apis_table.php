<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateLocalValidApisTable extends AbstractMigration
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
         * 系统内置 前置校验接口   例如: 国政通等
         */
        $table = $this->table('local_valid_apis');
        $table->addColumn('name', 'string', ['null' => FALSE])
              ->addColumn('status', 'boolean', ['default' => TRUE, 'comment' => '状态'])
              ->addColumn('class_name', 'string', ['null' => FALSE, 'comment' => '文件名称'])
              ->addColumn('config', 'json', ['null' => TRUE])
              ->save();
    }
}
