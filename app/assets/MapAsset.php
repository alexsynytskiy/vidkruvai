<?php

namespace app\assets;

/**
 * Class MapAsset
 * @package app\assets
 */
class MapAsset extends \yii\web\AssetBundle
{
    /**
     * @var string
     */
    protected static $pathToImages;

    public $sourcePath = '@app/media';

    public $css = [
        'plugins/mapsvg/css/mapsvg.css',
        'plugins/mapsvg/css/nanoscroller.css',
    ];

    public $js = [
        'plugins/mapsvg/js/jquery.mousewheel.min.js',
        'plugins/mapsvg/js/jquery.nanoscroller.min.js',
        'plugins/mapsvg/raphael.js',
        'plugins/mapsvg/js/mapsvg.min.js',
    ];

    public $publishOptions = ['forceCopy' => true];
}
