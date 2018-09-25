<?php

namespace app\models\definitions;

use app\components\AppMsg;
use app\components\BaseDefinition;

/**
 * Class DefUserAward
 * @package app\models\definitions
 */
class DefUserAward extends BaseDefinition
{
    /**
     * Types
     */
    const TYPE_ACHIEVEMENT = 'achievement';
    const TYPE_LEVEL = 'level';

    /**
     * @param string $returnType
     *
     * @see BaseDefinition::getListDataByReturnType()
     *
     * @return array
     */
    public static function getListTypes($returnType = 'key-value')
    {
        $statuses = [
            self::TYPE_ACHIEVEMENT => AppMsg::t('Досягнення'),
            self::TYPE_LEVEL => AppMsg::t('Рівень'),
        ];

        return static::getListDataByReturnType($statuses, $returnType);
    }
}
