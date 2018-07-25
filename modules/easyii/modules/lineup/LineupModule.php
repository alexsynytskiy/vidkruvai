<?php
namespace yii\easyii\modules\lineup;

class LineupModule extends \yii\easyii\components\Module
{
    public $settings = [
        'categoryThumb' => true,
        'lineupThumb' => true,
        'enablePhotos' => true,

        'enableShort' => true,
        'shortMaxLength' => 255,
        'enableTags' => true,

        'itemsInFolder' => false,
        'color' => true,
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Line Up',
            'ru' => 'Line Up',
        ],
        'icon' => 'pencil',
        'order_num' => 65,
    ];
}