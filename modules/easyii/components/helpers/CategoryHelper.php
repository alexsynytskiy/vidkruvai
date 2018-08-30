<?php

namespace yii\easyii\components\helpers;

/**
 * Class CategoryHelper
 * @package yii\easyii\components\helpers
 */
class CategoryHelper
{
    /**
     * Const allowed languages
     */
    const CATEGORY_NEWS = 'news';

    /**
     * @return array
     */
    public static function getCategories()
    {
        return [
            self::CATEGORY_NEWS => 'Новости'
        ];
    }

    /**
     * @return array
     */
    public static function getCategoriesValues()
    {
        return [
            self::CATEGORY_NEWS
        ];
    }
}
