<?php

namespace app\components\achievement;

use app\models\SiteUser;
use app\models\Team;

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
        /** @var SiteUser|Team $entity */
        $entity = self::getEntity($params['entityId'], $params['entityType']);

        if ($entity->level->num >= $params['required_steps']) {
            return $params['required_steps'];
        }

        return $entity->level->num;
    }
}
