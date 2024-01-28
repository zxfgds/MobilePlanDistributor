<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateBannersTable extends AbstractMigration
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
        // 名称  图片  类型  起止日期 投放筛选 (shop/channel , all | only| except) , 页面限制:('all','except','only'),类型('always','once")
        $table = $this->table('banners');
        $table->addColumn('name', 'string', ['null' => FALSE, 'comment' => '名称'])
              ->addColumn('image', 'string', ['null' => FALSE, 'comment' => '图片'])
              ->addColumn('type', 'integer', ['null' => TRUE, 'default' => 0, 'comment' => '幻灯类型,0:幻灯片 ,1:弹出广告', 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY])
              ->addColumn('show_config', 'json', ['comment' => 'type:all/only/except,ids:...'])
              ->addColumn('start_at', 'datetime')
              ->addColumn('end_at', 'datetime')
              ->addColumn('page_config', 'json', ['comment' => '显示页面 type:all,except,only'])
              ->addColumn('show_once', 'boolean', ['default' => FALSE, 'comment' => '只显示一次(用于弹窗广告)'])
              ->save();
        
    }
}
