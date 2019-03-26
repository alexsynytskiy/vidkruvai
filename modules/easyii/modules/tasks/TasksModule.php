<?php

namespace yii\easyii\modules\tasks;

/**
 * Class TasksModule
 * @package yii\easyii\modules\tasks
 */
class TasksModule extends \yii\easyii\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Tasks',
            'ru' => 'Завдання',
        ],
        'icon' => 'tasks',
        'order_num' => 210,
    ];
}
