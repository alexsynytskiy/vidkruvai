<?php

namespace yii\easyii\modules\notification;

/**
 * Class TeamsModule
 * @package yii\easyii\modules\notification
 */
class NotificationModule extends \yii\easyii\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Notification',
            'ru' => 'Сповіщення',
        ],
        'icon' => 'education',
        'order_num' => 100,
    ];
}
