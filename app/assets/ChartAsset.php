<?php

namespace app\assets;

/**
 * Class ChartAsset
 * @package app\assets
 */
class ChartAsset extends \yii\web\AssetBundle
{
    /**
     * @var string
     */
    protected static $pathToImages;

    public $sourcePath = '@app/media';

    public $js = [
        'https://www.chartjs.org/dist/2.8.0/Chart.min.js'
    ];

    public $publishOptions = ['forceCopy' => true];
}
