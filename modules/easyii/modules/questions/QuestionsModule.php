<?php
namespace yii\easyii\modules\questions;

class QuestionsModule extends \yii\easyii\components\Module
{
    public $settings = [
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Questions',
            'ru' => 'Вопросы',
        ],
        'icon' => 'bullhorn',
        'order_num' => 70,
    ];
}