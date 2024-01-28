<?php


use Phinx\Seed\AbstractSeed;

class AdminMenuSeeder extends AbstractSeed
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
            [
                'id'        => 1,
                'path'      => '/',
                'name'      => 'index',
                'component' => 'Layout',
                'pid'       => 0,
                'order'     => 0,
                'type'      => 0,
                'meta'      => [
                    'hidden'           => FALSE,
                    'levelHidden'      => FALSE,
                    'title'            => '首页',
                    'icon'             => 'home-2-line',
                    'badge'            => '',
                    'dot'              => FALSE,
                    'breadcrumbHidden' => FALSE,
                    'noClosable'       => FALSE,
                    'noKeepAlive'      => FALSE,
                ],
            ],
            [
                'id'        => 2,
                'name'      => 'Dashboard',
                'path'      => '/dashboard',
                'component' => 'Layout',
                'pid'       => 1,
                'order'     => 0,
                'type'      => 0,
                'meta'      => [
                    'hidden'           => FALSE,
                    'levelHidden'      => FALSE,
                    'title'            => '看板',
                    'icon'             => 'dashboard-3-line',
                    'badge'            => '',
                    'dot'              => FALSE,
                    'breadcrumbHidden' => FALSE,
                    'noClosable'       => FALSE,
                    'noKeepAlive'      => FALSE,
                ],
            ],
            [
                'id'        => 4,
                'name'      => 'Setting',
                'path'      => '/setting',
                'component' => 'Layout',
                'pid'       => 0,
                'order'     => 99,
                'type'      => 0,
                'meta'      => [
                    'hidden'           => FALSE,
                    'levelHidden'      => FALSE,
                    'title'            => '设置',
                    'icon'             => 'list-settings-fill',
                    'badge'            => '',
                    'dot'              => FALSE,
                    'breadcrumbHidden' => FALSE,
                    'noClosable'       => FALSE,
                    'noKeepAlive'      => FALSE,
                ],
            ],
            [
                'id'        => 7,
                'name'      => 'Management',
                'path'      => '/admin',
                'component' => 'Layout',
                'pid'       => 0,
                'order'     => 999,
                'type'      => 0,
                'meta'      => [
                    'hidden'           => FALSE,
                    'levelHidden'      => FALSE,
                    'title'            => '管理',
                    'icon'             => 'admin-line',
                    'badge'            => '',
                    'dot'              => FALSE,
                    'breadcrumbHidden' => FALSE,
                    'noClosable'       => FALSE,
                    'noKeepAlive'      => FALSE,
                ],
            ],
            [
                'id'        => 9,
                'name'      => 'AdminRole',
                'path'      => 'admin/role',
                'component' => 'admin/role/index',
                'pid'       => 7,
                'order'     => 0,
                'type'      => 0,
                'meta'      => [
                    'hidden'           => FALSE,
                    'levelHidden'      => FALSE,
                    'title'            => '角色',
                    'icon'             => 'folder-user-line',
                    'badge'            => '',
                    'dot'              => FALSE,
                    'breadcrumbHidden' => FALSE,
                    'noClosable'       => FALSE,
                    'noKeepAlive'      => FALSE,
                ],
            ],
            [
                'id'        => 8,
                'name'      => 'AdminUser',
                'path'      => 'admin/user',
                'component' => 'admin/user/index',
                'pid'       => 7,
                'order'     => 0,
                'type'      => 0,
                'meta'      => [
                    'hidden'           => FALSE,
                    'levelHidden'      => FALSE,
                    'title'            => '用户',
                    'icon'             => 'user-line',
                    'badge'            => '',
                    'dot'              => FALSE,
                    'breadcrumbHidden' => FALSE,
                    'noClosable'       => FALSE,
                    'noKeepAlive'      => FALSE,
                ],
            ],
            [
                'id'        => 10,
                'name'      => 'AdminMenu',
                'path'      => 'admin/menu',
                'component' => 'admin/menu/index',
                'pid'       => 7,
                'order'     => 4,
                'type'      => 0,
                'meta'      => [
                    'hidden'           => FALSE,
                    'levelHidden'      => FALSE,
                    'title'            => '菜单',
                    'icon'             => 'menu-line',
                    'badge'            => '',
                    'dot'              => FALSE,
                    'breadcrumbHidden' => FALSE,
                    'noClosable'       => FALSE,
                    'noKeepAlive'      => FALSE,
                ],
            ],
            [
                'id'        => 12,
                'name'      => 'PaySetting',
                'path'      => 'setting/pay',
                'component' => 'setting/pay/index',
                'pid'       => 4,
                'order'     => 6,
                'type'      => 0,
                'meta'      => [
                    'hidden'           => FALSE,
                    'levelHidden'      => FALSE,
                    'title'            => '支付设置',
                    'icon'             => 'money-cny-box-line',
                    'badge'            => '',
                    'dot'              => FALSE,
                    'breadcrumbHidden' => FALSE,
                    'noClosable'       => FALSE,
                    'noKeepAlive'      => FALSE,
                ],
            ],
            [
                'name'      => 'StorageSetting',
                'path'      => 'setting/storage',
                'component' => 'setting/storage/index',
                'pid'       => 4,
                'order'     => 6,
                'type'      => 0,
                'meta'      => [
                    'hidden'           => FALSE,
                    'levelHidden'      => FALSE,
                    'title'            => '存储设置',
                    'icon'             => 'money-cny-box-line',
                    'badge'            => '',
                    'dot'              => FALSE,
                    'breadcrumbHidden' => FALSE,
                    'noClosable'       => FALSE,
                    'noKeepAlive'      => FALSE,
                ],
            ],
            [
                'id'        => 25,
                'name'      => 'Product',
                'path'      => '/product',
                'component' => 'Layout',
                'pid'       => 0,
                'sort'      => 50,
                'meta'      => [
                    "hidden"           => FALSE,
                    "levelHidden"      => FALSE,
                    "title"            => "产品",
                    "icon"             => "product-hunt-line",
                    "badge"            => "",
                    "dot"              => FALSE,
                    "breadcrumbHidden" => FALSE,
                    "noClosable"       => FALSE,
                    "noKeepAlive"      => FALSE,
                ],
            ],
            [
                'name'      => 'Package',
                'path'      => '/product/package',
                'component' => 'product/package/index',
                'pid'       => 25,
                'sort'      => 0,
                'meta'      => [
                    "hidden"           => FALSE,
                    "levelHidden"      => FALSE,
                    "title"            => "资费",
                    "icon"             => "dropbox-line",
                    "badge"            => "",
                    "dot"              => FALSE,
                    "breadcrumbHidden" => FALSE,
                    "noClosable"       => FALSE,
                    "noKeepAlive"      => FALSE,
                ],
            ],
        ];
        
        foreach ($data as $key => $menu) {
            $menu['meta'] = json_encode($menu['meta']);
            $data[$key]   = $menu;
            
        }
        
        $table = $this->table('admin_menus');
        $table->insert($data)->save();
//        foreach ($data as $menu) {
//            $table->insert($menu)->save();
//        }
    }
}
