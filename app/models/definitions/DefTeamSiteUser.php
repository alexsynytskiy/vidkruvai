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
    const STATUS_DECLINED = 'declined';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_UNCONFIRMED = 'unconfirmed';
    const STATUS_UNSUBSCRIBED = 'unsubscribed';
    const STATUS_REMOVED = 'removed';

    const ROLE_MEMBER = 'member';
    const ROLE_CAPTAIN = 'captain';

    const RESPONSE_DECLINED = 'declined';
    const RESPONSE_REMOVED = 'removed';

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
     * @param string $key
     * @return mixed
     */
    public static function getStatusText($key)
    {
        $types = [
            self::STATUS_DECLINED => AppMsg::t('Запрошення відхилено'),
            self::STATUS_CONFIRMED => AppMsg::t('Підтверджено'),
            self::STATUS_UNCONFIRMED => AppMsg::t('Очікує підтвердження'),
            self::STATUS_REMOVED => AppMsg::t('Видалено'),
        ];

        return $types[$key];
    }

    /**
     * @param $type
     * @return mixed
     */
    public static function getStatusByType($type) {
        $types = [
            self::RESPONSE_DECLINED => self::STATUS_DECLINED,
            self::RESPONSE_REMOVED => self::STATUS_REMOVED,
        ];

        return $types[$type];
    }

    /**
     * @param string $key
     * @return mixed
     */
    public static function getSimpleRole($key)
    {
        $types = [
            self::ROLE_MEMBER => AppMsg::t('Учасник'),
            self::ROLE_CAPTAIN => AppMsg::t('Капітан'),
        ];

        return $types[$key];
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
