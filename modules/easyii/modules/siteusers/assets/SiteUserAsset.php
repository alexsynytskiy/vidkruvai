<?php

namespace yii\easyii\modules\siteusers\assets;

/**
 * Class SchoolAsset
 * @package yii\easyii\modules\siteusers\assets
 */
class SiteUserAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@easyii/modules/siteusers/media';

    public $js = [
        'pnotify/pnotify.min.js',
        'js/SiteUserCore.js',
        'js/SiteUserForm.js',
    ];

    public $css = [
        'pnotify/pnotify.min.css',
        'css/style.css',
    ];

    public $publishOptions = [
        'forceCopy' => true
    ];
}
