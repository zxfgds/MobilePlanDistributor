<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateRegionsTable extends AbstractMigration
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
        $table = $this->table('regions');
        $table
            ->addColumn('level', 'integer', ['signed' => FALSE, 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'comment' => '层级'])
            ->addColumn('parent_code', 'biginteger', ['signed' => FALSE, 'default' => 0, 'comment' => '父级行政代码'])
            ->addColumn('area_code', 'biginteger', ['signed' => FALSE, 'default' => 0, 'comment' => '行政代码'])
            ->addColumn('zip_code', 'integer', ['signed' => FALSE, 'limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_MEDIUM, 'default' => '000000', 'comment' => '邮政编码'])
            ->addColumn('city_code', 'string', ['limit' => 6, 'default' => '', 'comment' => '区号'])
            ->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '名称'])
            ->addColumn('short_name', 'string', ['limit' => 50, 'default' => '', 'comment' => '简称'])
            ->addColumn('merger_name', 'string', ['limit' => 50, 'default' => '', 'comment' => '组合名'])
            ->addColumn('pinyin', 'string', ['limit' => 30, 'default' => '', 'comment' => '拼音'])
            ->addColumn('lng', 'decimal', ['precision' => 10, 'scale' => 6, 'default' => 0, 'comment' => '经度'])
            ->addColumn('lat', 'decimal', ['precision' => 10, 'scale' => 6, 'default' => 0, 'comment' => '纬度'])
            ->addIndex(['area_code'], ['unique' => TRUE, 'name' => 'uk_code'])
            ->addIndex(['parent_code'], ['name' => 'idx_parent_code'])
            ->create();
    }
}
