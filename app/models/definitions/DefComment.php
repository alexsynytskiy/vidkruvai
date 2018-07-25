<?php

namespace app\models\definitions;

use app\components\AppMsg;
use app\components\BaseDefinition;

/**
 * Class DefLandingComment
 * @package acp\models\definitions
 */
class DefComment extends BaseDefinition
{
    /**
     * Statuses
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_MODERATOR = 'moderator';
    const STATUS_DELETED = 'deleted';

    /**
     * @param string $returnType
     *
     * @return array
     */
    public static function getListStatuses($returnType = 'key-value')
    {
        $statuses = [
            self::STATUS_ACTIVE => AppMsg::t('Активный'),
            self::STATUS_MODERATOR => AppMsg::t('На модерации'),
            self::STATUS_DELETED => AppMsg::t('Удален'),
        ];

        return static::getListDataByReturnType($statuses, $returnType);
    }

    /**
     * @param string $returnType
     *
     * @return array
     */
    public static function getListStatusesChannel($returnType = 'key-value')
    {
        $statuses = [
            self::STATUS_ACTIVE => AppMsg::t('Только активные'),
            self::STATUS_MODERATOR => AppMsg::t('На модерации'),
            self::STATUS_DELETED => AppMsg::t('Удаленные'),
        ];

        return static::getListDataByReturnType($statuses, $returnType);
    }
}
