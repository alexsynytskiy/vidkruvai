<?php

namespace app\models\definitions;

use app\components\AppMsg;
use app\components\BaseDefinition;

/**
 * Class DefTeam
 * @package app\models\definitions
 */
class DefTeam extends BaseDefinition
{
    const STATUS_ACTIVE = 'active';
    const STATUS_UNCONFIRMED = 'unconfirmed';
    const STATUS_BANNED = 'banned';
    const STATUS_DISABLED = 'disabled';

    /**
     * @param string $returnType
     *
     * @see BaseDefinition::getListDataByReturnType()
     *
     * @return array
     */
    public static function getStatuses($returnType = 'key-value')
    {
        $statuses = [
            self::STATUS_ACTIVE => AppMsg::t('Підтверджено'),
            self::STATUS_UNCONFIRMED => AppMsg::t('Очікує підтвердження'),
            self::STATUS_DISABLED => AppMsg::t('Відхилено'),
        ];

        return static::getListDataByReturnType($statuses, $returnType);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public static function getStatusText($key)
    {
        $statuses = [
            self::STATUS_ACTIVE => AppMsg::t('Підтверджено'),
            self::STATUS_UNCONFIRMED => AppMsg::t('Очікує підтвердження'),
            self::STATUS_BANNED => AppMsg::t('Заблоковано'),
            self::STATUS_DISABLED => AppMsg::t('Відключено'),
        ];

        return $statuses[$key];
    }
}
