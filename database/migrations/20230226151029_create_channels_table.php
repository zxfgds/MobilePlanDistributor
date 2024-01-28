<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateChannelsTable extends AbstractMigration
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
        $table = $this->table('channels');
        $table->addColumn('shop_id', 'integer', ['comment' => '店铺ID'])
              ->addColumn('config', 'json', ['comment' => '配置', 'null' => TRUE])
              ->addColumn('name', 'string', ['null' => FALSE, 'comment' => '渠道名称'])
              ->save();
    }
}
