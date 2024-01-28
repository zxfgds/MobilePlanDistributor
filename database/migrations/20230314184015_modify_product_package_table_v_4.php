<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class ModifyProductPackageTableV4 extends AbstractMigration
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
        $table = $this->table('product_packages');
        $table->addColumn('tags', 'json', ['null' => TRUE, 'comment' => '特点'])
              ->addColumn('is_recommend', 'integer', ['default' => 0, "comment" => "推荐"])
              ->addColumn('to_index', 'boolean', ['default' => FALSE, 'comment' => '首页展示'])
              ->save();
    }
}
