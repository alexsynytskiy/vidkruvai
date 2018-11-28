<?php

namespace yii\easyii\modules\teams\assets;

/**
 * Class TeamAsset
 * @package yii\easyii\modules\teams\assets
 */
class TeamAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@easyii/modules/teams/media';

    public $js = [
        'pnotify/pnotify.min.js',
        'js/TeamsCore.js',
        'js/TeamsForm.js',
        'js/TeamsIndex.js'
    ];

    public $css = [
        'pnotify/pnotify.min.css',
        'css/style.css',
    ];

    public $publishOptions = [
        'forceCopy' => true
    ];
}
