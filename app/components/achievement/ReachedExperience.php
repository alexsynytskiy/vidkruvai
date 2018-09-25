<?php

namespace app\components\achievement;

use app\models\SiteUser;

/**
 * Class ReachedExperience
 * @package app\components\achievement
 */
class ReachedExperience extends BaseAchievementRule
{
    const CLASS_NAME = 'ReachedExperience';

    /**
     * @param array $params
     *
     * @return bool|int|mixed
     */
    public static function execute(array $params = [])
    {
        /** @var SiteUser $user */
        $user = SiteUser::findOne($params['userId']);

        $params['performed_steps'] = $user->total_experience;

        if (parent::isLastAchievement(self::CLASS_NAME, $params)) {
            return true;
        }

        if ($params['performed_steps'] >= $params['required_steps']) {
            return $params['required_steps'];
        }

        return $params['performed_steps'];
    }
}
