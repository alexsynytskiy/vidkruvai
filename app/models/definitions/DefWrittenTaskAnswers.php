<?php

namespace app\models\definitions;

use app\components\AppMsg;
use app\components\BaseDefinition;

/**
 * Class DefWrittenTaskAnswers
 * @package app\models\definitions
 */
class DefWrittenTaskAnswers extends BaseDefinition
{
    const STATUS_DONE = 'done';
    const STATUS_ALL = 'all';
    const STATUS_NOT_DONE = 'not done';

    /**
     * @param string $returnType
     *
     * @see BaseDefinition::getListDataByReturnType()
     *
     * @return array
     */
    public static function getStatuses($returnType = 'key-value')
    {
        $types = [
            self::STATUS_DONE => AppMsg::t('Виконано'),
            self::STATUS_ALL => AppMsg::t('Всі'),
            self::STATUS_NOT_DONE => AppMsg::t('Не виконано'),
        ];

        return static::getListDataByReturnType($types, $returnType);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public static function getStatusText($key)
    {
        $types = [
            self::STATUS_DONE => AppMsg::t('Виконано'),
            self::STATUS_NOT_DONE => AppMsg::t('Не виконано'),
        ];

        return $types[$key];
    }
}
