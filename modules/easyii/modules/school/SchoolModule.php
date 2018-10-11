<?php

namespace yii\easyii\modules\school;

/**
 * Class SchoolModule
 * @package yii\easyii\modules\school
 */
class SchoolModule extends \yii\easyii\components\Module
{

    public static $installConfig = [
        'title' => [
            'en' => 'Schools',
            'ru' => 'Школи',
        ],
        'icon' => 'th-list',
        'order_num' => 105,
    ];
}
