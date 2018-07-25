<?php
namespace yii\easyii\modules\customs;

class CustomsModule extends \yii\easyii\components\Module
{
    public $settings = [
        'enableThumb' => false,
        'enablePhotos' => true,
        'enableShort' => false,
        'shortMaxLength' => 256,
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Customs',
            'ru' => 'Таможенные пункты',
        ],
        'icon' => 'question-sign',
        'order_num' => 95,
    ];
}