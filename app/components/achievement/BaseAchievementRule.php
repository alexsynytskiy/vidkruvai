<?php

namespace app\components\achievement;

use app\models\Achievement;
use app\models\definitions\DefEntityAchievement;
use app\models\SiteUser;
use app\models\Team;

/**
 * Class BaseAchievementRule
 * @package app\components\achievement
 */
abstract class BaseAchievementRule implements IBaseAchievementRule
{
    /**
     * @param       $className
     * @param array $params
     *
     * @return bool
     */
    public static function isLastAchievement($className, array $params = [])
    {
        /** @var Achievement $achievement */
        $achievement = Achievement::find()
            ->where(['class_name' => $className, 'entity_type' => $params['entityType']])
            ->andWhere(['>', 'required_steps', $params['required_steps']])
            ->one();

        return !$achievement && $params['required_steps'] > $params['performed_steps'];
    }

    /**
     * @param int $entityId
     * @param string $entityType
     *
     * @return SiteUser|Team|null
     */
    public static function getEntity($entityId, $entityType)
    {
        $entity = null;

        if($entityType === DefEntityAchievement::ENTITY_USER) {
            $entity = SiteUser::find()->where(['id' => $entityId])->one();
        }
        elseif ($entityType === DefEntityAchievement::ENTITY_TEAM) {
            $entity = Team::find()->where(['id' => $entityId])->one();
        }

        return $entity;
    }
}
