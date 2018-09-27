<?php

namespace app\components\achievement;

use app\models\Achievement;

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
            ->where(['class_name' => $className])
            ->andWhere(['>', 'required_steps', $params['required_steps']])
            ->one();

        return !$achievement && $params['required_steps'] > $params['performed_steps'];
    }
}
