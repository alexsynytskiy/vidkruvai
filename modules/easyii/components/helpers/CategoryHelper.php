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
    const CATEGORY_PORTFOLIO = 'portfolio';

    /**
     * @return array
     */
    public static function getCategories()
    {
        return [
            self::CATEGORY_NEWS => 'Новости',
            self::CATEGORY_PORTFOLIO => 'Портфолио',
        ];
    }

    /**
     * @return array
     */
    public static function getCategoriesValues()
    {
        return [
            self::CATEGORY_NEWS,
            self::CATEGORY_PORTFOLIO,
        ];
    }
}
