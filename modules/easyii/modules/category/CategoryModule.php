<?php

namespace yii\easyii\modules\category;

/**
 * Class CategoryModule
 * @package yii\easyii\modules\category
 */
class CategoryModule extends \yii\easyii\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Categories',
            'ru' => 'Категорії',
        ],
        'icon' => 'category',
        'order_num' => 210,
    ];
}
