<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateAdminMenusTable extends AbstractMigration
{
    public function change()
    {

//         {"hidden":FALSE,"levelHidden":FALSE,"title":"New Menu","icon":"align-justify","badge":"new","dot":FALSE,"breadcrumbHidden":FALSE,"noClosable":FALSE,"noKeepAlive":FALSE}
        $table = $this->table('admin_menus');
        $table->addColumn('name', 'string', ['limit' => 255, 'default' => '', 'comment' => '名称'])
              ->addColumn('path', 'string', ['limit' => 255, 'default' => '', 'comment' => '路径'])
              ->addColumn('component', 'string', ['limit' => 255, 'default' => 'Layout', 'comment' => '模板'])
              ->addColumn('redirect', 'string', ['limit' => 255, 'null' => TRUE, 'comment' => '跳转'])
              ->addColumn('pid', 'integer', ['default' => 0, 'comment' => '上级'])
              ->addColumn('type', 'integer', ['default' => 0, 'comment' => '类型'])
              ->addColumn('sort', 'integer', ['default' => 0, 'comment' => '排序'])
              ->addColumn('meta', 'json', ['null' => TRUE, 'comment' => '属性'])
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'comment' => '创建时间'])
              ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'comment' => '更新时间'])
              ->create();
    }
    
    public function down()
    {
        $this->dropTable('admin_menus');
    }
}
