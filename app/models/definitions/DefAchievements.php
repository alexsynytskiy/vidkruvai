<?php

namespace app\models\definitions;

use app\components\AppMsg;
use app\components\BaseDefinition;

/**
 * Class DefAchievements
 * @package app\models\definition
 */
class DefAchievements extends BaseDefinition
{
    const NAMESPACE_RULES = 'app\components\achievement\\';

    /**
     * Statuses
     */
    const STATUS_ALL = 'all';
    const STATUS_ACHIEVED = 'achieved';
    const STATUS_AVAILABLE = 'available';
    const STATUS_IN_PROGRESS = 'inprogress';

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
            self::STATUS_ALL => AppMsg::t('Всі'),
            self::STATUS_ACHIEVED => AppMsg::t('Досягнуті'),
            self::STATUS_AVAILABLE => AppMsg::t('Доступні'),
            self::STATUS_IN_PROGRESS => AppMsg::t('В процесі'),
        ];

        return static::getListDataByReturnType($statuses, $returnType);
    }

    public static function getStatus($key)
    {
        $statuses = [
            self::STATUS_ALL => AppMsg::t('Всі'),
            self::STATUS_ACHIEVED => AppMsg::t('Досягнуті'),
            self::STATUS_AVAILABLE => AppMsg::t('Доступні'),
            self::STATUS_IN_PROGRESS => AppMsg::t('В процесі'),
        ];

        return $statuses[$key];
    }
}
