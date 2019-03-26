<?php

namespace app\models\definitions;

use app\components\AppMsg;
use app\components\BaseDefinition;

/**
 * Class DefTask
 * @package app\models\definitions
 */
class DefTask extends BaseDefinition
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    const TYPE_TEST = 'test';
    const TYPE_WRITTEN = 'written';

    const MISSED = 'missed';
    const DISABLED = 'disabled';
    const ACTIVE = 'active';
    const ANSWERED = 'answered';

    /**
     * @param string $returnType
     *
     * @see BaseDefinition::getListDataByReturnType()
     *
     * @return array
     */
    public static function getTypes($returnType = 'key-value')
    {
        $statuses = [
            self::TYPE_TEST => AppMsg::t('Тест'),
            self::TYPE_WRITTEN => AppMsg::t('Письмове завдання'),
        ];

        return static::getListDataByReturnType($statuses, $returnType);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public static function getTypeText($key)
    {
        $statuses = [
            self::TYPE_TEST => AppMsg::t('Тест'),
            self::TYPE_WRITTEN => AppMsg::t('Письмове завдання'),
        ];

        return $statuses[$key];
    }

    /**
     * @param string $key
     * @return mixed
     */
    public static function getStateText($key)
    {
        $statuses = [
            self::MISSED => AppMsg::t('Час на відповідь вийшов'),
            self::DISABLED => AppMsg::t('Буде доступно пізніше'),
            self::ACTIVE => AppMsg::t('Очікує на відповідь'),
            self::ANSWERED => AppMsg::t('Відповідь отримано'),
        ];

        return $statuses[$key];
    }

    /**
     * @return mixed
     */
    public static function getStatuses()
    {
        $statuses = [
            self::STATUS_ON => AppMsg::t('Активне'),
            self::STATUS_OFF => AppMsg::t('Не активне'),
        ];

        return $statuses;
    }

    /**
     * @return mixed
     */
    public static function getRequired()
    {
        $statuses = [
            self::STATUS_ON => AppMsg::t('Обов\'язкове'),
            self::STATUS_OFF => AppMsg::t('Не обов\'язкове'),
        ];

        return $statuses;
    }
}
