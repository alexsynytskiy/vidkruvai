<?php

namespace app\components\achievement;

use app\models\SiteUser;

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
        /** @var SiteUser $user */
        $user = SiteUser::findOne($params['userId']);

        return $user ? 1 : 0;
    }
}
