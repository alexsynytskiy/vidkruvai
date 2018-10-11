<?php

namespace yii\easyii\modules\siteusers;

/**
 * Class SiteUsersModule
 * @package yii\easyii\modules\siteusers
 */
class SiteUsersModule extends \yii\easyii\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Site Users',
            'ru' => 'Пользователи',
        ],
        'icon' => 'list-alt',
        'order_num' => 100,
    ];
}
