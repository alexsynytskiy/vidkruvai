<?php

namespace yii\easyii\modules\teams;

/**
 * Class TeamsModule
 * @package yii\easyii\modules\teams
 */
class TeamsModule extends \yii\easyii\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Teams',
            'ru' => 'Команди',
        ],
        'icon' => 'education',
        'order_num' => 100,
    ];
}
