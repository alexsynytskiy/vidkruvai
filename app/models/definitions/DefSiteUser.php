<?php

namespace app\models\definitions;

use app\components\AppMsg;
use app\components\BaseDefinition;
use app\models\SiteUser;

/**
 * Class DefSiteUser
 * @package app\models\definitions
 */
class DefSiteUser extends BaseDefinition
{
    /**
     * Statuses
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_BLOCKED = 'blocked';

    /**
     * @param string $returnType
     *
     * @return array
     */
    public static function getListStatuses($returnType = 'key-value')
    {
        $list = [
            self::STATUS_ACTIVE => AppMsg::t('Активирован'),
            self::STATUS_INACTIVE => AppMsg::t('Не активирован'),
            self::STATUS_BLOCKED => AppMsg::t('Заблокирован'),
        ];

        return static::getListDataByReturnType($list, $returnType);
    }

    /**
     * @param string $returnType
     *
     * @see BaseDefinition::getListDataByReturnType()
     *
     * @return array
     */
    public static function getListUserRoles($returnType = 'key-value')
    {
        $roles = [
            SiteUser::ROLE_PARTICIPANT => AppMsg::t('Учасник'),
            SiteUser::ROLE_MENTOR => AppMsg::t('Ментор'),
        ];

        return static::getListDataByReturnType($roles, $returnType);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public static function getUserRoleText($key)
    {
        $roles = [
            SiteUser::ROLE_PARTICIPANT => AppMsg::t('Учасник'),
            SiteUser::ROLE_MENTOR => AppMsg::t('Ментор'),
        ];

        return $roles[$key];
    }

    /**
     * @return array
     */
    public static function getRoles()
    {
        return [
            SiteUser::ROLE_PARTICIPANT => 'Учасник',
            SiteUser::ROLE_MENTOR => 'Ментор',
        ];
    }
}
