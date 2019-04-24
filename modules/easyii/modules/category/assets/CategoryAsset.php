<?php

namespace yii\easyii\modules\category\assets;

/**
 * Class CategoryAsset
 * @package yii\easyii\modules\category\assets
 */
class CategoryAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@easyii/modules/category/media';

    public $js = [
        'pnotify/pnotify.min.js',
        'js/CategoryCore.js',
        'js/CategoryForm.js',
    ];

    public $css = [
        'pnotify/pnotify.min.css',
        'css/style.css',
    ];

    public $publishOptions = [
        'forceCopy' => true
    ];
}
