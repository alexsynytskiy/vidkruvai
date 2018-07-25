<?php
namespace yii\easyii\assets;

class IndexAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@easyii/media';
    public $css = [
        'css/chartjs-visualizations.css',
    ];
    public $js = [
        'js/view-selector2.js',
        'js/date-range-selector.js',
        'js/active-users.js',
    ];
    public $depends = [
        'yii\easyii\assets\AdminAsset',
    ];
    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );
}