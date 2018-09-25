<?php

namespace app\models\definitions;

use app\components\AppMsg;
use app\components\BaseDefinition;

/**
 * Class DefLandingAward
 * @package app\models\definitions
 */
class DefAward extends BaseDefinition
{
    /**
     * Types
     */
    const TYPE_EXPERIENCE = 'experience';

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
            self::TYPE_EXPERIENCE => AppMsg::t('Досвід'),
        ];

        return static::getListDataByReturnType($statuses, $returnType);
    }
}
