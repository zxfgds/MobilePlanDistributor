<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class ChangeOperatorApisTableV1 extends AbstractMigration
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
        $table->addColumn('product_info_status', 'boolean', ['default' => FALSE, 'comment' => '是否需要配置产品信息'])
              ->addColumn('product_info_config', 'json', ['comment' => '产品信息需要字段'])
              ->save();
    }
}
