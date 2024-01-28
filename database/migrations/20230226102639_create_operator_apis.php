<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateOperatorApis extends AbstractMigration
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
        $table = $this->table('operator_apis');
        $table->addColumn('name', 'string', ['null' => FALSE])
              ->addColumn('slug', 'string', ['null' => FALSE, 'comment' => '类名称'])
              ->addColumn('operator', 'integer', ['default' => 1, 'comment' => '运营商'])
              ->addColumn('config', 'json', ['null' => TRUE, 'comment' => '配置'])
              ->save();
        
    }
}
