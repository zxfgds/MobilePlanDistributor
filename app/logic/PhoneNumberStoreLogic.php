<?php

namespace app\logic;

use app\model\PhoneNumberStore;
use Exception;

class PhoneNumberStoreLogic extends BaseLogic
{
    protected static string $model = PhoneNumberStore::class;
    
    
    /**
     * Get a list of items based on specific conditions
     *
     * @param array  $conditions An array of conditions to filter the results
     * @param int    $pageSize   The number of items per page
     * @param int    $page       The page number
     * @param string $sortBy     The field to sort by
     * @param string $sortOrder  The sorting order, either 'asc' or 'desc'
     *
     * @return array The list of items
     * @throws Exception
     */
    public static function getList(array $conditions = [], int $pageSize = 20, int $page = 1, string $sortBy = 'id', string $sortOrder = 'asc'): array
    {
        // Get the list of items from the parent class
        $data = parent::getList($conditions, $pageSize, $page, $sortBy, $sortOrder);
        
        // Get the type of each item and set the corresponding table name
        $list = $data['list'];
        foreach ($list as $key => $value) {
            $table = match ($value['type']) {
                PhoneNumberStore::TYPE_PACKAGE => "product_package_numbers",
                PhoneNumberStore::TYPE_FANCY => "product_fancy_numbers",
                default => "product_numbers",
            };
            
            // Get the count of numbers for each store from the appropriate table
            $list[$key]['number_count'] = \support\Db::table($table)->where('store_id', $value['id'])->count();
        }
        
        // Update the list with the number count and return
        $data['list'] = $list;
        return $data;
    }
    
}