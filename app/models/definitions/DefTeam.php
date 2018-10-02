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
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_UNCONFIRMED = 'UNCONFIRMED';
    const STATUS_BANNED = 'BANNED';
    const STATUS_DISABLED = 'DISABLED';

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
            self::STATUS_UNCONFIRMED => AppMsg::t('Непідтверджено'),
            self::STATUS_BANNED => AppMsg::t('Заблоковано'),
            self::STATUS_DISABLED => AppMsg::t('Відключено'),
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
            self::STATUS_UNCONFIRMED => AppMsg::t('Непідтверджено'),
            self::STATUS_BANNED => AppMsg::t('Заблоковано'),
            self::STATUS_DISABLED => AppMsg::t('Відключено'),
        ];

        return $statuses[$key];
    }
}
