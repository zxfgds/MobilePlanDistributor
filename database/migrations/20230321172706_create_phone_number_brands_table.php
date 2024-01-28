<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreatePhoneNumberBrandsTable extends AbstractMigration
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
        $this->table('number_brands')->drop()->save();
        $table = $this->table('number_brands');
        $table->addColumn('phone_num', 'string', ['null' => TRUE, 'comment' => '客服电话'])
              ->addColumn('name', 'string')
              ->addColumn('website', 'string')
              ->addColumn('active_url', 'string')
              ->addColumn('active_info', 'text')
              ->addColumn('remark', 'text')
              ->addIndex('name', ['unique' => TRUE])
              ->save();
    }
}
