<?php

namespace yii\easyii\modules\writtentasksanswers;

/**
 * Class WrittenTasksAnswersModule
 * @package yii\easyii\modules\writtentasksanswers
 */
class WrittenTasksAnswersModule extends \yii\easyii\components\Module
{
    public static $installConfig = [
        'title' => [
            'en' => 'Written Tasks Answers',
            'ru' => 'Письмові відповіді',
        ],
        'icon' => 'written-tasks-answers',
        'order_num' => 200,
    ];
}
