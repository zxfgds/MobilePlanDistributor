<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class ChangePackageNumberTableV1 extends AbstractMigration
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
        
        $statusNames = \app\model\Number::$STATUS_NAMES;
        
        $string = '';
        foreach ($statusNames as $status => $name) {
            $string .= $status . ":" . $name . ';';
        }
        $table = $this->table('package_numbers');
        $table->removeColumn('type')
              ->addColumn('province_code', 'string', ['default' => 0])
              ->addColumn('city_code', 'string', ['default' => 0])
              ->addColumn('status', 'integer', ['limit' => 4, 'default' => 0, 'comment' => $string])
              ->save();
    }
}
