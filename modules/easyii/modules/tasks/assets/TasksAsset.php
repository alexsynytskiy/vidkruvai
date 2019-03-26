<?php

namespace yii\easyii\modules\tasks\assets;

/**
 * Class TasksAsset
 * @package yii\easyii\modules\tasks\assets
 */
class TasksAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@easyii/modules/tasks/media';

    public $js = [
        'pnotify/pnotify.min.js',
        'js/TasksCore.js',
        'js/TasksIndex.js'
    ];

    public $css = [
        'pnotify/pnotify.min.css',
        'css/style.css',
    ];

    public $publishOptions = [
        'forceCopy' => true
    ];
}
