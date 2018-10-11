<?php

namespace yii\easyii\modules\school\assets;

/**
 * Class SchoolAsset
 * @package yii\easyii\modules\school\assets
 */
class SchoolAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@easyii/modules/school/media';

    public $js = [
        'pnotify/pnotify.min.js',
        'js/SchoolCore.js',
        'js/CreateSchoolPage.js',
    ];

    public $css = [
        'pnotify/pnotify.min.css',
        'css/style.css',
    ];

    public $publishOptions = [
        'forceCopy' => true
    ];
}
