<?php

namespace app\commands;

use yii\console\Controller;

/**
 * Class FlushAssetCacheController
 * @package app\commands
 */
class FlushAssetCacheController extends Controller
{
    protected $paths = [
        'media/',
        'vendor/noumo/easyii/media/',
    ];

    /**
     * Change time of the modification of the directories
     * It forces AssetManager to republish directories
     */
    public function actionIndex() {
        $date = date('YmdHi.s');

        foreach($this->paths as $path) {
            exec("touch -t {$date} {$path}");
        }
    }
}