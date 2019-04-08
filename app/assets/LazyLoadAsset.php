<?php

namespace app\assets;

/**
 * Class LazyLoadAsset
 * @package app\assets
 */
class LazyLoadAsset extends \yii\web\AssetBundle
{
    /**
     * @var string
     */
    protected static $pathToImages;

    public $sourcePath = '@app/media';

    public $js = [
        'https://cdn.jsdelivr.net/npm/vanilla-lazyload@11.0.6/dist/lazyload.min.js'
    ];

    public $publishOptions = ['forceCopy' => true];
}
