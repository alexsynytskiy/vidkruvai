<?php

namespace yii\easyii\modules\statistics;

/**
 * Class StatisticsModule
 * @package yii\easyii\modules\statistics
 */
class StatisticsModule extends \yii\easyii\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Statistics',
            'ru' => 'Статистика',
        ],
        'icon' => 'statistics',
        'order_num' => 200,
    ];
}
