<?php

namespace app\components\helpers;

/**
 * Class SidebarHelper
 * @package app\components\helpers
 */
class SidebarHelper
{
    /**
     * @param $items
     * @param $statuses
     * @param $currentCategory
     */
    public static function checkItems($items, &$statuses, $currentCategory)
    {
        foreach ($items as $key => $item) {
            if (isset($item['items']) && count($item['items']) > 0) {
                static::checkItems($item['items'], $statuses, $currentCategory);
            } else {
                if ((isset($item['visible']) && $item['visible']) || !isset($item['visible'])) {
                    $statuses[$currentCategory] = true;
                    return;
                }
            }
        }
    }

    /**
     * @param array $menuItems
     *
     * @return array
     */
    public static function getFilteredMenu($menuItems)
    {
        $currentCategory = 0;
        $categoryStatuses = [];

        foreach ($menuItems as $item) {
            if (isset($item['categoryId'])) {
                $currentCategory = $item['categoryId'];
            }

            if (isset($item['inCategory']) && $item['inCategory'] === $currentCategory) {

                if ((isset($item['visible']) && $item['visible']) || (!isset($item['visible']) && !isset($item['items']))) {
                    $categoryStatuses[$currentCategory] = true;
                }

                if (isset($item['items'])) {
                    static::checkItems($item['items'], $categoryStatuses, $currentCategory);
                }
            }
        }

        foreach ($menuItems as $key => $item) {
            if (isset($item['categoryId']) && !array_key_exists($item['categoryId'], $categoryStatuses)) {
                unset($menuItems[$key]);
            }
        }

        return $menuItems;
    }
}
