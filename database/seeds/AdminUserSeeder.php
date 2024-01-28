<?php


use Phinx\Seed\AbstractSeed;

class AdminUserSeeder extends AbstractSeed
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
        $data = [
            'id'       => 1,
            'name'     => '系统管理员',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'is_super' => TRUE,
        ];
        
        $table = $this->table('admin_users');
        $table->insert($data)->save();
    }
}
