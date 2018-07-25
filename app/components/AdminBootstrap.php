<?php

namespace app\components;

use yii\base\BootstrapInterface;

class AdminBootstrap implements BootstrapInterface {
    /**
     * @param \yii\web\Application $app
     */
    public function bootstrap($app)
    {
        $pathInfo = $app->request->pathInfo;

        if(strpos($pathInfo, 'admin') !== false) {
            $app->setComponents([
                'assetManager' => [
                    'class'   => 'yii\web\AssetManager',
                    'bundles' => [
                        'yii\web\JqueryAsset' => [
                            'js' => [YII_DEBUG ? 'jquery.js' : 'jquery.min.js'],
                        ],
                        'yii\bootstrap\BootstrapAsset' => [
                            'css' => [YII_DEBUG ? 'css/bootstrap.css' : 'css/bootstrap.min.css'],
                        ],
                        'yii\bootstrap\BootstrapPluginAsset' => [
                            'js' => [YII_DEBUG ? 'js/bootstrap.js' : 'js/bootstrap.min.js'],
                        ],
                    ],
                ],
            ]);
        }
    }
}
