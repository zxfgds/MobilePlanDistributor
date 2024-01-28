<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class ChangeProductPackagesTableV2 extends AbstractMigration
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
        $table->addColumn('sale_mode', 'boolean', ['default' => FALSE, 'comment' => '0:普通模式, 1:卖号模式,卖号模式下  以号码价格为准'])->save();
    }
}
