<?php

namespace app\models\definitions;

use app\components\AppMsg;
use app\components\BaseDefinition;

/**
 * Class DefStoreItem
 * @package app\models\definitions
 */
class DefStoreItem extends BaseDefinition
{
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
    public static function getListStatuses($returnType = 'key-value')
    {
        $statuses = [
            self::STATUS_ACTIVE => AppMsg::t('Активен'),
            self::STATUS_INACTIVE => AppMsg::t('Не активен'),
        ];

        return static::getListDataByReturnType($statuses, $returnType);
    }
}
