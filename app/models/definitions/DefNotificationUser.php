<?php

namespace app\models\definitions;

use app\components\AppMsg;
use app\components\BaseDefinition;

/**
 * Class DefNotificationUser
 * @package app\models\definitions
 */
class DefNotificationUser extends BaseDefinition
{
    /**
     * Statuses
     */
    const STATUS_NEW = 'new';
    const STATUS_READ = 'read';
    const STATUS_ARCHIVED = 'archived';

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
            self::STATUS_NEW => AppMsg::t('Нове'),
            self::STATUS_READ => AppMsg::t('Прочитано'),
            self::STATUS_ARCHIVED => AppMsg::t('Архів'),
        ];

        return static::getListDataByReturnType($statuses, $returnType);
    }
}
