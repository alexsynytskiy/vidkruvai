<?php

namespace app\models\definitions;

use app\components\AppMsg;
use app\components\BaseDefinition;

/**
 * Class DefCategory
 * @package app\models\definitions
 */
class DefCategory extends BaseDefinition
{
    /**
     * Types
     */
    const TYPE_ACHIEVEMENT = 'achievement';
    const TYPE_ACHIEVEMENT_GROUP = 'achievement-group';
    const TYPE_LEVEL = 'level';
    const TYPE_LEVEL_GROUP = 'level-group';
    const TYPE_STORE_CATEGORY = 'store-category';
    const TYPE_STORE_CATEGORY_LEVEL = 'store-category-level';
    /**
     * Statuses
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

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
            self::TYPE_ACHIEVEMENT => AppMsg::t('Достижения'),
            self::TYPE_LEVEL => AppMsg::t('Уровни'),
            self::TYPE_LEVEL_GROUP => AppMsg::t('Группы уровней'),
            self::TYPE_ACHIEVEMENT_GROUP => AppMsg::t('Группы достижений'),
            self::TYPE_STORE_CATEGORY => AppMsg::t('Категории магазин'),
            self::TYPE_STORE_CATEGORY_LEVEL => AppMsg::t('Уровни категорий магазин'),
        ];

        return static::getListDataByReturnType($types, $returnType);
    }

    /**
     * @param string $key
     *
     * @see BaseDefinition::getListDataByReturnType()
     *
     * @return array
     */
    public static function getListType($key)
    {
        $types = [
            self::TYPE_ACHIEVEMENT => AppMsg::t('Достижения'),
            self::TYPE_LEVEL => AppMsg::t('Уровни'),
            self::TYPE_LEVEL_GROUP => AppMsg::t('Группы уровней'),
            self::TYPE_ACHIEVEMENT_GROUP => AppMsg::t('Группы достижений'),
            self::TYPE_STORE_CATEGORY => AppMsg::t('Категории магазин'),
            self::TYPE_STORE_CATEGORY_LEVEL => AppMsg::t('Уровни категорий магазин'),
        ];

        return $types[$key];
    }

    /**
     * @param string $returnType
     *
     * @see BaseDefinition::getListDataByReturnType()
     *
     * @return array
     */
    public static function getListStatuses($returnType = 'key-value')
    {
        $statuses = [
            self::STATUS_ACTIVE => AppMsg::t('Активен'),
            self::STATUS_INACTIVE => AppMsg::t('Не активен'),
        ];

        return static::getListDataByReturnType($statuses, $returnType);
    }
}
