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
                    'icon' => 'fa fa-newspaper-o',
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
                    'icon' => 'fa fa-user-plus',
                    'icon-border-color' => 'border-success',
                    'icon-color' => 'text-success',
                    'short-title' => 'Користувач підтвердив участь',
                    'title' => 'Користувач тепер у команді',
                    'message' => 'Користувач {team_member} підтвердив свою участь у команді! Дата підтвердження {created_at}',
                    'scenario' => [
                        'sendIfNotRead' => false,
                        'sendPeriod' => 24,
                    ],
                ],
                DefNotification::TYPE_TEAM_USER_CANCELLED => [
                    'icon' => 'fa fa-user-times',
                    'icon-border-color' => 'border-failure',
                    'icon-color' => 'text-failure',
                    'short-title' => 'Користувач відхилив участь',
                    'title' => 'Користувач відхилив запрошення у команду',
                    'message' => 'Користувач {team_member} відхилив свою участь у команді! Дата відхилення {created_at}',
                    'scenario' => [
                        'sendIfNotRead' => false,
                        'sendPeriod' => 24,
                    ],
                ],
                DefNotification::TYPE_TEAM_INVITATION => [
                    'icon' => 'fa fa-envelope-o',
                    'icon-border-color' => 'border-success',
                    'icon-color' => 'text-success',
                    'short-title' => 'Отримано запрошення у команду',
                    'title' => 'Вам надійшло запрошення у команду',
                    'message' => 'Капітан {team_captain} надіслав вам запрошення у команду {team_name}! 
                        Щоб прийняти запрошення тисни {accept}, щоб відхилити тисни {decline}. 
                        Дата отримання {created_at}',
                    'scenario' => [
                        'sendIfNotRead' => false,
                        'sendPeriod' => 24,
                    ],
                ],
                DefNotification::TYPE_USER_PROFILE_PROBLEM => [
                    'icon' => 'fa fa-user-md',
                    'icon-border-color' => 'border-failure',
                    'icon-color' => 'text-failure',
                    'short-title' => 'Виникла помилка профілю!',
                    'title' => 'Виникла помилка профілю! Терміново внесіть зміни',
                    'message' => 'Було виявлено помилку профілю! Перейди за посиланням, та виправ якомога швидше. Дата отримання {created_at}',
                    'scenario' => [
                        'sendIfNotRead' => false,
                        'sendPeriod' => 24,
                    ],
                ],
                DefNotification::TYPE_MENTOR_NOTIFICATION => [
                    'icon' => 'fa fa-user-secret',
                    'icon-border-color' => 'border-success',
                    'icon-color' => 'text-success',
                    'short-title' => 'Отримано повідомлення від організаторів',
                    'title' => 'Вам надійшло повідомлення від організаторів проекту',
                    'message' => 'Сьогодні о 19:00 відбудеться вебінар, на якому ви отримаєте усі відповіді про проект, реєстрацію, етапи, структуру, роль ментора та багато іншого! Детальніше за посиланням {link_news} Дата отримання {created_at}',
                    'scenario' => [
                        'sendIfNotRead' => false,
                        'sendPeriod' => 24,
                    ],
                ],
                DefNotification::TYPE_ALL_NOTIFICATION => [
                    'icon' => 'fa fa-user-secret',
                    'icon-border-color' => 'border-success',
                    'icon-color' => 'text-success',
                    'short-title' => 'Отримано повідомлення від організаторів',
                    'title' => 'Вам надійшло повідомлення від організаторів проекту',
                    'message' => 'Нові завдання у ваших кабінетах - останні ДЕДЛАЙНИ 2018 року тут  - {link_news} Дата отримання {created_at}',
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
