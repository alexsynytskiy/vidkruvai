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
    public function actionIndex()
    {
        $date = date('YmdHi.s');

        foreach ($this->paths as $path) {
            exec("touch -t {$date} {$path}");
        }
    }

    /**
     * @return void
     */
    public function actionTranslations()
    {
        $acpJsTranslations = require(__DIR__ . '/../modules/messages/JsTranslations.php');

        $acpJsFile = __DIR__ . '/../media/js/js-translations.js';

        $acpJsVar = 'var Translations = ';

        file_put_contents($acpJsFile, $acpJsVar . json_encode([
                'uk' => $acpJsTranslations,
                'en' => $acpJsTranslations,
            ]) . ';');
    }
}