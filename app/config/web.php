<?php

$params = require(__DIR__ . '/params.php');

$basePath = dirname(__DIR__);
$webroot = dirname($basePath);

$config = [
    'id' => 'app',
    'basePath' => $basePath,
    'bootstrap' => [
        'log',
        "app\\components\\AdminBootstrap",
    ],
    'language' => 'uk',
    'sourceLanguage' => 'uk',
    'timeZone' => 'Europe/Kiev',
    'runtimePath' => $webroot . '/runtime',
    'vendorPath' => $webroot . '/vendor',
    'on beforeRequest' => function () use (&$config) {
        $app = Yii::$app;
        $pathInfo = $app->request->pathInfo;

        $getParam = $app->request->get('parent');
        preg_match('/[^\/]+$/', $pathInfo, $matches);

        $startRedirect = ['admin', 'site', 'profile'];
        $stopRedirect = ['items', 'edit', 'photos', 'settings', 'index', 'list', 'redactor', 'all', 'account'];

        $redirect = false;

        if (empty($getParam) && (isset($matches[0]) && !is_numeric($matches[0]))) {
            foreach ($startRedirect as $startItem) {
                if (strpos($pathInfo, $startItem) !== false) {
                    $redirect = true;
                    break;
                }
            }

            foreach ($stopRedirect as $stopItem) {
                if (strpos($pathInfo, $stopItem) !== false) {
                    $redirect = false;
                    break;
                }
            }
        }

        if (!$app->request->post() && $redirect && !empty($pathInfo) && substr($pathInfo, -1) !== '/') {
            $app->response->redirect('/' . rtrim($pathInfo) . '/', 301);
            $app->end();
        }
    },
    'modules' => [
        'comment' => [
            'class' => "app\\modules\\comment\\Module",
        ],
        'system' => [
            'class' => "app\\modules\\events\\Module",
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '5EQS1r3ySDhlyuurHzud',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
        ],
        'siteUser' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\SiteUser',
            'enableAutoLogin' => true,
            'authTimeout' => 60 * 30,
            'loginUrl' => ['/login'],
            'identityCookie' => [
                'name' => '_panelSiteUser',
            ]
        ],
        'notification' => [
            'class' => 'app\components\notification\Notification',
        ],
        'mutex' => [
            'class' => 'yii\mutex\FileMutex',
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'cookieParams' => ['lifetime' => 7 * 24 * 60 * 60],
        ],
        'urlManager' => [
            'rules' => [
                'generate' => 'test/generate',
                'register' => 'profile/register',
                'login' => 'profile/login',
                'comment/<channelId:\d+>/<action:[\w-]+>' => 'comment/default/<action>',
                'comment/<action:[\w-]+>' => 'comment/default/<action>',

                'profile/notifications/<category:[a-z\d-]+>/<status:[a-z\d-]+>' => 'profile/notifications',
                'profile/notifications/<category:[a-z\d-]+>' => 'profile/notifications',

                '<controller:\w+>/' => '<controller>/index',
                '<controller:\w+>/<slug:[\w-]+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
                '<controller:\w+>/cat/<slug:[\w-]+>' => '<controller>/cat',
            ],
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'bundles' => [
                //Disable this bundle, because we have our jquery
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,   // do not publish the bundle
                    'js' => [],
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => null,   // do not publish the bundle
                    'css' => [],
                    'js' => [],
                ],
                //Overrides standard yii.activeForm.js file
                //@see https://github.com/yiisoft/yii2/issues/12174
                'yii\widgets\ActiveFormAsset' => [
                    'js' => [
                        'yii.activeForm.js',
                    ],
                    'depends' => [
                        'yii\web\YiiAsset',
                    ],
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'on app.components.AchievementComponent.on-achieved' => function ($event) {
        (new app\components\AwardEventHandler)->award($event);
    },
    'on app.components.LevelComponent.on-unlocked' => function ($event) {
        (new app\components\AwardEventHandler)->award($event);
    },
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';

    $config['components']['db']['enableSchemaCache'] = false;
}

return \yii\helpers\ArrayHelper::merge($config, require($webroot . '/modules/easyii/config/easyii.php'));