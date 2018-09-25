<?php

namespace app\components\achievement;

/**
 * Interface IBaseAchievementRule
 * @package app\components\achievement
 */
interface IBaseAchievementRule
{
    public static function execute(array $params = []);

    public static function isLastAchievement($className, array $params = []);
}
