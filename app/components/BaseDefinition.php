<?php

namespace app\components;

/**
 * Class BaseDefinition
 * @package acp\components
 */
class BaseDefinition
{
    /**
     * Orders
     */
    const ORDER_ASC = SORT_ASC;
    const ORDER_DESC = SORT_DESC;

    /**
     * Parse list array and return required data for incoming option
     *
     * @param array $data
     * @param string $returnType define structure of return array.
     *                           Allow variants: { 'key-value', 'keys', 'values' }
     *
     * @return array
     */
    public static function getListDataByReturnType($data, $returnType = 'key-value')
    {
        switch ($returnType) {
            default:
            case 'key-value':
                return $data;
            case 'keys':
                return array_keys($data);
            case 'values':
                return array_values($data);
        }
    }

    /**
     * @param string $returnType
     *
     * @return array
     */
    public static function getListPublishedStatuses($returnType = 'key-value')
    {
        $statuses = [
            ActiveRecord::IS_PUBLISH => AppMsg::t('Опубликован'),
            ActiveRecord::IS_NOT_PUBLISH => AppMsg::t('Не опубликован'),
        ];

        return static::getListDataByReturnType($statuses, $returnType);
    }

    /**
     * @param string $returnType
     *
     * @return array
     */
    public static function getListArchivedStatuses($returnType = 'key-value')
    {
        $statuses = [
            ActiveRecord::IS_ARCHIVED => AppMsg::t('Архивирован'),
            ActiveRecord::IS_NOT_ARCHIVED => AppMsg::t('Активен'),
        ];

        return static::getListDataByReturnType($statuses, $returnType);
    }
}
