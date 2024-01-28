<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateNumberBrandsTable extends AbstractMigration
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
        
        $table = $this->table('number_brands');
        
        $table->addColumn('name', 'string', ['null' => FALSE, 'comment' => '品牌名称'])
              ->addColumn('website', 'string', ['null' => TRUE, 'comment' => '品牌名称'])
              ->addColumn('active_url', 'string', ['null' => TRUE, 'comment' => '激活链接'])
              ->addColumn('active_intro', 'string', ['null' => TRUE, 'comment' => '激活说明'])
              ->addColumn('remark', 'string', ['null' => TRUE, 'comment' => '备注'])
              ->save();
    }
}
