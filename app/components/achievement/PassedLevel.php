<?php

namespace app\components\achievement;

use app\models\SiteUser;

/**
 * Class PassedLevel
 * @package app\components\achievement
 */
class PassedLevel extends BaseAchievementRule
{
    const CLASS_NAME = 'PassedLevel';

    /**
     * @param array $params
     *
     * @return int
     */
    public static function execute(array $params = [])
    {
        /** @var SiteUser $user */
        $user = SiteUser::findOne($params['userId']);

        if ($user->level->num >= $params['required_steps']) {
            return $params['required_steps'];
        }

        return $user->level->num;
    }
}
