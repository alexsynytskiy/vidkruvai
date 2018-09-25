<?php

namespace app\models\definitions;

use app\components\AppMsg;
use app\components\BaseDefinition;

/**
 * Class DefLevel
 * @package app\models\definitions
 */
class DefLevel extends BaseDefinition
{
    /**
     * Statuses
     */
    const STATUS_ALL = 'all';
    const STATUS_ACHIEVED = 'achieved';
    const STATUS_AVAILABLE = 'available';

    const BASE_LEVEL = 1;

    const NEXT_LEVELS = '>';
    const PREVIOUS_LEVELS = '<';

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
        ];

        return static::getListDataByReturnType($statuses, $returnType);
    }
}
