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
            'useFileTransport' => false,//set this property to false to send mails to real email addresses
            //comment the following array to send mail using php's mail function
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host'  => 'mx1.mirohost.net',
                'username' => 'alexsynytskiy@coukraine.org',
                'password' => 'z91XRQpzgqXA',
                'port'     => 25,
                'encryption' => 'tls',
            ],
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
                'login' => 'profile/login',
                'rules' => 'profile/rules',

                'comment/<channelId:\d+>/<action:[\w-]+>' => 'comment/default/<action>',
                'comment/<action:[\w-]+>' => 'comment/default/<action>',

                'profile/notifications/<category:[a-z\d-]+>/<status:[a-z\d-]+>' => 'profile/notifications',
                'profile/notifications/<category:[a-z\d-]+>' => 'profile/notifications',

                'register/<hash:[\w-]+>' => 'profile/register',
                'register' => 'profile/register',

                'profile/clear-image/<id:[\d+]+>/<className:[\w+]+>' => 'profile/clear-image',
                'team/clear-image/<id:[\d+]+>/<className:[\w+]+>' => 'team/clear-image',

                'profile/create-team' => 'team/create-team',

                '<controller:\w+>' => '<controller>/index',
                '<controller:\w+>/<slug:[\w-]+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
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
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'uk',
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'app' => 'translations.php',
                    ]
                ]
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
    'on beforeRequest' => function () {
        (new \app\components\TrailingSlashHelper)->redirectSlash();
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