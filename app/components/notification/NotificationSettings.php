<?php

namespace app\components\notification;

use app\models\definitions\DefNotification;
use yii\helpers\ArrayHelper;

/**
 * Class NotificationSettings
 * @package app\components\notification
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
                    'icon' => 'fa fa-user', //Icon showed on notification page and at the top menu
                    'icon-border-color' => 'border-success', //Color of the icon border
                    'icon-color' => 'text-success', //Color of the icon
                    'short-title' => 'Новий користувач', //Short title will show at the top menu
                    'title' => 'Реєстрація нового користувача', //Title will show on notification page
                    'message' => 'В системі зареєстровано нового користувача {user_link}. 
                                Дата реєстрації {created_at}', //Message will show on notification page
                    'scenario' => [
                        'sendIfNotRead' => false,
                        'sendPeriod' => 24,
                    ],
                ],
                DefNotification::TYPE_HELLO_USER => [
                    'icon' => 'fa fa-home',
                    'icon-border-color' => 'border-primary',
                    'icon-color' => 'text-primary',
                    'short-title' => 'Привіт!',
                    'title' => 'Привіт!',
                    'message' => 'Це сповіщення. Тут будя вся важлива інформація для твого аккаунту',
                    'scenario' => [
                        'sendIfNotRead' => false,
                        'sendPeriod' => 24,
                    ],
                ],
                DefNotification::TYPE_NEWS_ADDED => [
                    'icon' => 'icon-newspaper',
                    'icon-border-color' => 'border-success',
                    'icon-color' => 'text-success',
                    'short-title' => 'Нова новина',
                    'title' => 'Додано нову новину',
                    'message' => 'Ознайомитись з новиною {news_link}. Дата додавання {created_at}',
                    'scenario' => [
                        'sendIfNotRead' => false,
                        'sendPeriod' => 24,
                    ],
                ],
                DefNotification::TYPE_TEAM_CREATED => [
                    'icon' => 'fa fa-users',
                    'icon-border-color' => 'border-success',
                    'icon-color' => 'text-success',
                    'short-title' => 'Команду створено',
                    'title' => 'Створено нову команду',
                    'message' => 'Створено команду {team_name}, очікуй на підтвердження учасників та верифікацію. Дата створення {created_at}',
                    'scenario' => [
                        'sendIfNotRead' => false,
                        'sendPeriod' => 24,
                    ],
                ],
                DefNotification::TYPE_TEAM_USER_ACCEPTED => [
                    'icon' => 'fa fa-users',
                    'icon-border-color' => 'border-success',
                    'icon-color' => 'text-success',
                    'short-title' => 'Користувач підтвердив участь',
                    'title' => 'Користувач тепер у команді',
                    'message' => 'Користувач {team_member} підтвердив свою участь у команді! Дата підтвердження {created_at}',
                    'scenario' => [
                        'sendIfNotRead' => false,
                        'sendPeriod' => 24,
                    ],
                ]
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
