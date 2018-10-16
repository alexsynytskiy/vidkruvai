<?php

namespace app\components\achievement;

use app\models\SiteUser;
use app\models\Team;

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
        /** @var SiteUser|Team $entity */
        $entity = self::getEntity($params['entityId'], $params['entityType']);

        $params['performed_steps'] = $entity->total_experience;

        if (parent::isLastAchievement(self::CLASS_NAME, $params)) {
            return true;
        }

        if ($params['performed_steps'] >= $params['required_steps']) {
            return $params['required_steps'];
        }

        return $params['performed_steps'];
    }
}
