<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class ModifyAdminMenusTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('admin_menus');
        $table->changeColumn('type', 'integer', ['default' => 0, 'comment' => '类型', 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY])
              ->save();
    }
}
