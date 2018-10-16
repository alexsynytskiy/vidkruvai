<?php

namespace app\components\achievement;

use app\models\SiteUser;
use app\models\Team;

/**
 * Class RegistrationAchievement
 * @package app\components\achievement
 */
class RegistrationAchievement extends BaseAchievementRule
{
    const CLASS_NAME = 'RegistrationAchievement';

    /**
     * @param array $params
     *
     * @return int
     */
    public static function execute(array $params = [])
    {
        /** @var SiteUser|Team $entity */
        $entity = self::getEntity($params['entityId'], $params['entityType']);

        return $entity ? 1 : 0;
    }
}
