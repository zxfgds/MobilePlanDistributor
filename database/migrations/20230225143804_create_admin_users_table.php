<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateAdminUsersTable extends AbstractMigration
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
        $table = $this->table('admin_users');
        $table->addColumn('username', 'string', ['null' => FALSE])
              ->addColumn('name', 'string', ['null' => TRUE])
              ->addColumn('password', 'string')
              ->addColumn('avatar', 'string', ['null' => TRUE])
              ->addColumn('status', 'boolean', ['default' => TRUE])
              ->addColumn('wechat_open_id', 'string', ['null' => TRUE])
              ->save();
        
    }
}
