<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateArticlesTable extends AbstractMigration
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
        $table = $this->table('articles');
        $table->addColumn('title', 'string', ['null' => FALSE, 'comment' => '标题'])
              ->addColumn('author', 'string', ['null' => TRUE, 'comment' => '作者'])
              ->addColumn('cover', 'string', ['null' => TRUE])
              ->addColumn('content', 'text')
              ->save();
    }
}
