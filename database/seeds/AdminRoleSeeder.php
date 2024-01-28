<?php


use Phinx\Seed\AbstractSeed;

class AdminRoleSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $data  = [
            'id'       => 1,
            'name'     => '管理员',
            'intro'    => '系统管理员',
            'is_super' => TRUE,
        ];
        $table = $this->table('admin_roles');
        $table->insert($data)->save();
    }
}
