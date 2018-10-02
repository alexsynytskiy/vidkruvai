<?php

namespace app\models\definitions;

use app\components\AppMsg;
use app\components\BaseDefinition;
use app\models\SiteUser;

/**
 * Class DefTeamSiteUser
 * @package app\models\definitions
 */
class DefTeamSiteUser extends BaseDefinition
{
    const STATUS_DECLINED = 'DECLINED';
    const STATUS_CONFIRMED = 'CONFIRMED';
    const STATUS_UNCONFIRMED = 'UNCONFIRMED';

    const ROLE_MEMBER = 'member';
    const ROLE_CAPTAIN = 'captain';

    /**
     * @param string $returnType
     *
     * @see BaseDefinition::getListDataByReturnType()
     *
     * @return array
     */
    public static function getRoles($returnType = 'key-value')
    {
        $types = [
            self::ROLE_MEMBER => AppMsg::t('Учасник'),
            self::ROLE_CAPTAIN => AppMsg::t('Капітан'),
        ];

        return static::getListDataByReturnType($types, $returnType);
    }

    /**
     * @param string $teamRole
     * @param string $userRole
     * @return mixed
     */
    public static function getRoleText($teamRole, $userRole)
    {
        $types = [
            SiteUser::ROLE_MENTOR => [
                self::ROLE_MEMBER => AppMsg::t('Ментор'),
            ],
            SiteUser::ROLE_PARTICIPANT => [
                self::ROLE_MEMBER => AppMsg::t('Учасник'),
                self::ROLE_CAPTAIN => AppMsg::t('Капітан'),
            ]
        ];

        return $types[$userRole][$teamRole];
    }
}
