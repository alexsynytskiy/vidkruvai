<?php

namespace app\components\notification;

use app\models\definitions\DefNotification;
use yii\helpers\ArrayHelper;

/**
 * Class NotificationSettings
 * @package acp\components\notification
 */
class NotificationSettings
{
    /**
     * @var array
     */
    protected static $_settings = [];

    /**
     * Settings initialization
     */
    protected static function _initSettings()
    {
        if (empty(self::$_settings)) {
            self::$_settings = [
                DefNotification::TYPE_USER_REGISTRATION => [
                    'icon' => 'icon-user-plus', //Icon showed on notification page and at the top menu
                    'icon-border-color' => 'border-success', //Color of the icon border
                    'icon-color' => 'text-success', //Color of the icon
                    'short-title' => 'Новый пользователь', //Short title will show at the top menu
                    'title' => 'Регистрация нового пользователя', //Title will show on notification page
                    'message' => 'В системе зарегистрировался новый пользователь {user_link}. 
                                Дата регистрации {created_at}', //Message will show on notification page
                    'scenario' => [
                        'sendIfNotRead' => false,
                        'sendPeriod' => 24,
                    ],
                ],
                DefNotification::TYPE_HELLO_USER => [
                    'icon' => 'icon-info3',
                    'icon-border-color' => 'border-primary',
                    'icon-color' => 'text-primary',
                    'short-title' => 'Привет!',
                    'title' => 'Привет!',
                    'message' => 'Это уведомления. Тут будет собрана вся важная информация по Вашему аккаунту',
                    'scenario' => [
                        'sendIfNotRead' => false,
                        'sendPeriod' => 24,
                    ],
                ],
                DefNotification::TYPE_NEWS_ADDED => [
                    'icon' => 'icon-newspaper',
                    'icon-border-color' => 'border-success',
                    'icon-color' => 'text-success',
                    'short-title' => 'Добавлена новость',
                    'title' => 'Добавлена новость',
                    'message' => 'Ознакомиться с новостью {news_link}. Дата появления {created_at}',
                    'scenario' => [
                        'sendIfNotRead' => false,
                        'sendPeriod' => 24,
                    ],
                ],
            ];
        }
    }

    /**
     * @param      $key
     * @param null $defaultValue
     *
     * @return mixed
     */
    public static function getParam($key, $defaultValue = null)
    {
        self::_initSettings();

        return ArrayHelper::getValue(self::$_settings, $key, $defaultValue);
    }

    /**
     * @return array
     */
    public static function getSettings()
    {
        self::_initSettings();

        return self::$_settings;
    }
}
