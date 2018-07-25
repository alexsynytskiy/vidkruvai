<?php

namespace app\models\definitions;

use app\components\AppMsg;
use app\components\BaseDefinition;

/**
 * Class DefChangelog
 * @package acp\models\definitions
 */
class DefChangelog extends BaseDefinition
{
    const EVENT_INSERT  = 'insert';
    const EVENT_UPDATE  = 'update';
    const EVENT_DELETE  = 'delete';
    const EVENT_ARCHIVE = 'archive';

    /**
     * @param string $returnType
     *
     * @see BaseDefinition::getListDataByReturnType()
     *
     * @return array
     */
    public static function getListEvents($returnType = 'key-value') {
        $types = [
            self::EVENT_INSERT  => AppMsg::t('Добавление'),
            self::EVENT_UPDATE  => AppMsg::t('Обновление'),
            self::EVENT_DELETE  => AppMsg::t('Удаление'),
            self::EVENT_ARCHIVE => AppMsg::t('Архивирование'),
        ];

        return static::getListDataByReturnType($types, $returnType);
    }
}
