<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateAttachmentTable extends AbstractMigration
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
        $table = $this->table('attachments');
        $table->addColumn('url', 'string', ['null' => TRUE])
              ->addColumn('filename', 'string', ['null' => TRUE])
              ->addColumn('path', 'string', ['null' => TRUE])
              ->addColumn('is_hidden', 'boolean', ['default' => FALSE])
              ->addColumn('location', 'enum', ['values' => ['cloud', 'public', 'hidden']])
              ->create();
    }
}
