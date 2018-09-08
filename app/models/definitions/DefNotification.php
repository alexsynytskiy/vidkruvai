<?php

namespace app\models\definitions;

use app\components\AppMsg;
use app\components\BaseDefinition;

/**
 * Class DefNotification
 * @package app\models\definitions
 */
class DefNotification extends BaseDefinition
{
    /**
     * Categories
     */
    const CATEGORY_ACCOUNT = 'account';
    const CATEGORY_ACCOUNT_COLOR = 'info';
    const CATEGORY_NEWS = 'news';
    const CATEGORY_NEWS_COLOR = 'orange';
    const CATEGORY_TEAM = 'team';
    const CATEGORY_TEAM_COLOR = 'info';
    const CATEGORY_TASK = 'task';
    const CATEGORY_TASK_COLOR = 'info';

    /**
     * Types
     */
    const TYPE_USER_REGISTRATION = 'user-registration';
    const TYPE_NEWS_ADDED = 'news-added';
    const TYPE_HELLO_USER = 'hello-user';
    const TYPE_TEAM_CREATED = 'team-created';
    const TYPE_TEAM_ACTIVATED = 'team-activated';
    const TYPE_TASK_RECEIVED = 'task-received';

    /**
     * @param string $returnType
     *
     * @see BaseDefinition::getListDataByReturnType()
     *
     * @return array
     */
    public static function getListCategories($returnType = 'key-value')
    {
        $categories = [
            self::CATEGORY_ACCOUNT => [
                'title' => AppMsg::t('Аккаунт'),
                'color' => self::CATEGORY_ACCOUNT_COLOR,
            ],
            self::CATEGORY_NEWS => [
                'title' => AppMsg::t('Новини'),
                'color' => self::CATEGORY_NEWS_COLOR,
            ],
            self::CATEGORY_TEAM => [
                'title' => AppMsg::t('Команда'),
                'color' => self::CATEGORY_TEAM_COLOR,
            ],
            self::CATEGORY_TASK => [
                'title' => AppMsg::t('Завдання'),
                'color' => self::CATEGORY_TASK_COLOR,
            ],

        ];

        return static::getListDataByReturnType($categories, $returnType);
    }

    /**
     * @param string $returnType
     *
     * @see BaseDefinition::getListDataByReturnType()
     *
     * @return array
     */
    public static function getListTypes($returnType = 'key-value')
    {
        $types = [
            self::TYPE_USER_REGISTRATION => AppMsg::t('Реєстрація користувача'),
            self::TYPE_NEWS_ADDED => AppMsg::t('Добавлена новина'),
            self::TYPE_HELLO_USER => AppMsg::t('Привітання користувачу'),
            self::TYPE_TEAM_CREATED => AppMsg::t('Команду створено'),
            self::TYPE_TEAM_ACTIVATED => AppMsg::t('Команду активовано'),
            self::TYPE_TASK_RECEIVED => AppMsg::t('Отримано завдання'),
        ];

        return static::getListDataByReturnType($types, $returnType);
    }
}
