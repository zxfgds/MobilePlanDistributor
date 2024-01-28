<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateAutoBidRulesTable extends AbstractMigration
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
     *
     */
    
    
    public function change()
    {
        $rules = [
            [
                'condition' => [
                    'min_price' => 500,
                    'max_price' => 1000,
                ],
                'action'    => [
                    'type'   => 'fixed',
                    'amount' => 50,
                ],
            ],
            [
                'condition' => [
                    'min_price' => 1000,
                    'max_price' => 5000,
                ],
                'action'    => [
                    'type'   => 'percentage',
                    'amount' => 50,
                ],
            ],
        ];
        $table = $this->table('auto_bid_rules', ['id' => FALSE, 'primary_key' => 'id']);
        $table->addColumn('id', 'biginteger', ['signed' => FALSE, 'identity' => TRUE])
              ->addColumn('name', 'string', ['limit' => 50, 'comment' => '规则名称'])
              ->addColumn('rules', 'json', ['comment' => '加价规则'])
              ->addColumn('status', 'boolean', ['default' => TRUE, 'comment' => '状态'])
              ->addColumn('created_at', 'datetime', ['null' => FALSE, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '创建时间'])
              ->addColumn('updated_at', 'datetime', ['null' => FALSE, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '更新时间'])
              ->create();
    }
}
