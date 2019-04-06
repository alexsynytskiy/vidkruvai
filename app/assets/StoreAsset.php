<?php

namespace app\assets;

/**
 * Class StoreAsset
 * @package app\assets
 */
class StoreAsset extends \yii\web\AssetBundle
{
    /**
     * @var string
     */
    protected static $pathToImages;

    public $sourcePath = '@app/media';

    public $css = [
        'https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.3/css/uikit.min.css',
    ];

    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.3/js/uikit.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.3/js/uikit-icons.min.js',
    ];

    public $publishOptions = ['forceCopy' => true];
}
