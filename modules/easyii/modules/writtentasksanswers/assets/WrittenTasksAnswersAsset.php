<?php

namespace yii\easyii\modules\writtentasksanswers\assets;

/**
 * Class WrittenTasksAnswersAsset
 * @package yii\easyii\modules\writtentasksanswers\assets
 */
class WrittenTasksAnswersAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@easyii/modules/writtentasksanswers/media';

    public $js = [
        'pnotify/pnotify.min.js',
        'js/WrittenTasksAnswersCore.js',
        'js/WrittenTasksAnswersIndex.js'
    ];

    public $css = [
        'pnotify/pnotify.min.css',
        'css/style.css',
    ];

    public $publishOptions = [
        'forceCopy' => true
    ];
}
