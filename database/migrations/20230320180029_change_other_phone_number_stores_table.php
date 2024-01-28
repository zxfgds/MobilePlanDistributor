<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class ChangeOtherPhoneNumberStoresTable extends AbstractMigration
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
        
        $tables = [
            'package_number_stores',
            'product_virtual_number_stores',
            'virtual_number_stores',
        ];
        foreach ($tables as $tableName) {
            $this->table($tableName)->drop()->save();
        }
    }
}
