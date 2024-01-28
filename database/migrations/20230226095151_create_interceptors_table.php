<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateInterceptorsTable extends AbstractMigration
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
        $table = $this->table('interceptors');
        $table->addColumn('province', 'json', ['null' => TRUE])
              ->addColumn('city', 'json', ['null' => TRUE]) // 'default' => '{}'
              ->addColumn('age', 'json', ['null' => TRUE])  // 'default' => '{"max":0,"min":0}'
              ->save();
    }
}
