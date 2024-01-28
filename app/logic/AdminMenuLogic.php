<?php

namespace app\logic;

use app\model\AdminMenu;
use app\model\AdminRoleMenu;
use Illuminate\Support\Arr;
use RedisException;

class AdminMenuLogic extends BaseLogic
{
    protected static string $model    = AdminMenu::class;
    protected static bool   $useCache = TRUE;
    
    public static function getList(array $condition = [], int $pageSize = 20, int $page = 1, string $sortBy = 'sort', string $sortOrder = 'desc'): array
    {
        $sortBy = "sort";
        return parent::getList($condition, $pageSize, $page, $sortBy, $sortOrder);
    }
    
    /**
     * 格式化入库数据
     *
     * @param      $data
     * @param bool $isEdit
     *
     * @return array
     */
    public static function formatSaveData($data, bool $isEdit = FALSE): array
    {
        // todo: 重复了 重复了  合并到一起
        $data = parent::formatSaveData($data);
        return Arr::only($data, ['name', 'path', 'component', 'redirect', 'pid', 'meta', 'sort', 'type']);
    }
    
    /**
     * 获取菜单
     *
     * @param      $uid
     * @param bool $toTree
     * @param bool $isSuper
     *
     * @return array
     * @throws RedisException
     */
    public static function menus($uid, bool $toTree = FALSE, bool $isSuper = FALSE): array
    {
        $menus         = AdminMenu::orderBy('sort', 'asc')->get()->toArray();
        $formattedData = array_map([static::class, 'format'], $menus);
        
        $user = AdminUserLogic::getOne($uid);
        // super
        
        if (!$user['is_super']) {
            $menuIds = AdminRoleMenu::whereIn('role_id', $user['roles'])->orderBy('sort', 'desc')->pluck('menu_id')->toArray();
            foreach ($formattedData as $key => $menu) {
                if (!in_array($menu['id'], $menuIds)) unset($formattedData[$key]);
            }
        }
        
        return $toTree ? static::toTree($formattedData) : $formattedData;
    }
    
    
    /**
     * @param array $menus
     * @param int   $pid
     *
     * @return array
     */
    public static function toTree(array $menus, int $pid = 0): array
    {
        $tree = [];
        foreach ($menus as $key => $menu) {
            if ($menu['pid'] == $pid) {
                $menu['children'] = self::toTree($menus, $menu['id']);
                $tree[]           = $menu;
                unset($menus[$key]);
            }
        }
        return $tree;
    }
    
    
    /**
     * @param      $data
     * @param bool $isEdit
     *
     * @return array
     */
    public static function formatNew($data, bool $isEdit = FALSE): array
    {
        $menuData = [
            "name"      => $data['name'],
            "path"      => $data['path'] ?? '/',
            "component" => $data['component'] ?? "Layout",
            "pid"       => $data['pid'] ?? 0,
            "type"      => $data['type'] ?? 0,
            "sort"      => $data['sort'] ?? 0,
            "meta"      => [
                "hidden"           => $data['meta']['hidden'] ?? FALSE,
                "levelHidden"      => $data['meta']['levelHidden'] ?? FALSE,
                "title"            => $data['meta']['title'] ?? "New Menu",
                "icon"             => $data['meta']['icon'] ?? "align-justify",
                "badge"            => $data['meta']['badge'] ?? "",
                "dot"              => $data['meta']['dot'] ?? FALSE,
                "breadcrumbHidden" => $data['meta']['breadcrumbHidden'] ?? FALSE,
                "noClosable"       => $data['meta']['noCloseable'] ?? FALSE,
                "noKeepAlive"      => $data['meta']['noKeepAlive'] ?? FALSE,
            ],
        ];
        
        if (!empty($data['id'])) $menuData['id'] = $data['id'];
        
        return $menuData;
    }
    
}