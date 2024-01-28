<?php
declare(strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreatePhoneNumberStoresTable extends AbstractMigration
{
    /**
     * 创建号码库表
     *
     *
     */
    public function change(): void
    {
        $table = $this->table('phone_number_stores', ['comment' => '号码库']);
        $table->addColumn('name', 'string', ['null' => FALSE, 'comment' => '名称'])
              ->addColumn('shop_id', 'integer', ['default' => 0, 'null' => FALSE, 'comment' => '店铺'])
              ->addColumn('type', 'integer', ['default' => 0, 'comment' => '类型: 0 :虚商号库, 1:套餐号库'])
              ->addColumn('status', 'boolean', ['default' => 0, 'null' => FALSE, 'comment' => '状态; 0 等待审核, 1:审核中 2:审核失败  3:file'])
              ->addColumn('weight', 'integer', ['default' => 0, 'comment' => '权重'])
              ->save();
    }
}
