<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CrageTasksTable extends AbstractMigration
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
        // 创建 tasks 表格
        $table = $this->table('tasks', ['comment' => '任务表']);
        $table->addColumn('type', 'integer', [
            'default' => 0,
            'limit'   => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY,
            'comment' => '任务类型：0 : 导入号码',
        ])
              ->addColumn('content_type', 'integer', [
                  'default' => 0,
                  'limit'   => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY,
                  'comment' => '内容类型：0 - 文件, 1 - 文字',
              ])
              ->addColumn('content', 'text', [
                  'comment' => '任务内容',
              ])
              ->addColumn('status', 'integer', [
                  'default' => 0,
                  'limit'   => 2,
                  'comment' => '任务状态',
              ])
              ->addColumn('total_num', 'integer', [
                  'default' => 0,
                  'comment' => '任务总量',
              ])
              ->addColumn('current_num', 'integer', [
                  'default' => 0,
                  'comment' => '当前任务量',
              ])
              ->addColumn('start_at', 'datetime', [
                  'null'    => TRUE,
                  'comment' => '任务开始时间',
              ])
              ->addColumn('end_at', 'datetime', [
                  'null'    => TRUE,
                  'comment' => '任务结束时间',
              ])
              ->addColumn('message', 'text', [
                  'null'    => TRUE,
                  'comment' => '任务消息',
              ])
              ->addColumn('error_data', 'json', [
                  'null'    => TRUE,
                  'comment' => '错误数据',
              ])
              ->create();
    }
}
