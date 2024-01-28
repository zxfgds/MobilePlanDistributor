<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateRegionsTableV2 extends AbstractMigration
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
        
        if ($this->hasTable('regions')) {
            $this->table('regions')->drop()->save();
        }
        
        $table = $this->table('regions');
        $table->addColumn('level', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY])
              ->addColumn('name', 'string')
              ->addColumn('code', 'integer')
              ->addColumn('parent', 'integer', ['default' => 0])
              ->addIndex(['code', 'name', 'parent'])
              ->save();
        
    }
}
