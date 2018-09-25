<?php

namespace app\components;

use app\components\events\AwardEvent;
use app\models\Achievement;
use app\models\definitions\DefAchievements;
use app\models\definitions\DefUserAchievement;
use app\models\SiteUser;
use app\models\UserAchievement;
use Yii;
use yii\base\Component;
use yii\db\Expression;

/**
 * Class AchievementComponent
 * @package app\components
 */
class AchievementComponent extends Component
{
    protected static $isLastAchievement = false;

    /**
     * @return bool
     */
    public static function getLastAchievement()
    {
        return static::$isLastAchievement;
    }

    /**
     * Event name, uses when triggers achieve event
     */
    const EVENT_ACHIEVED = 'app.components.AchievementComponent.on-achieved';

    /**
     * @param       $achievementClass
     * @param       $userId
     * @param array $params
     *
     * @return bool
     * @throws \Exception
     */
    public static function isGoalAchieved($achievementClass, $userId, $params = [])
    {
        try {
            /** @var SiteUser $user */
            $user = SiteUser::find()->where(['id' => $userId])->one();

            if (!$user) {
                return false;
            }

            /** @var Achievement $achievement */
            $achievement = Achievement::getAchievementByClassNameAndUser($userId, $achievementClass);

            if (!$achievement) {
                return false;
            }

            $userAchievement = UserAchievement::getUserAchievementByID($achievementClass, $achievement->id, $userId);

            if ($userAchievement->is_first) {
                static::$isLastAchievement = false;

                return false;
            }

            if ($userAchievement !== null && $userAchievement->done) {
                return false;
            }

            if (!isset($params['userId'])) {
                $params['userId'] = $userId;
            }

            if (!isset($params['required_steps'])) {
                $params['required_steps'] = $achievement->required_steps;
            }

            $preformedSteps = call_user_func(DefAchievements::NAMESPACE_RULES . $achievementClass . '::execute', $params);

            if ($preformedSteps === true) {
                static::$isLastAchievement = true;
                $userAchievement->updateAttributes(['performed_steps' => $achievement->required_steps]);

                /** @var Achievement $firstAchievement */
                $firstAchievement = Achievement::getAchievementToIncrease($userId, $achievementClass, 'LEFT JOIN');
                $firstUserAchievement = UserAchievement::getUserAchievementByID($achievementClass, $firstAchievement->id, $userId);
                $firstUserAchievement->updateAttributes(['is_first' => 1]);

                return true;
            }

            $userAchievement->updateAttributes(['performed_steps' => $preformedSteps]);

            if (!empty($params['updateAchievementsGroup'])) {
                $achievementsToUpdate = Achievement::getAchievementsToUpdatePerformedSteps($achievementClass, $achievement->id);

                /** @var Achievement $item */
                foreach ($achievementsToUpdate as $item) {
                    /** @var UserAchievement $userStatus */
                    $userStatus = UserAchievement::getUserAchievementByID($item->class_name, $item->id, $userId);
                    $userStatus->updateAttributes(['performed_steps' => $preformedSteps]);
                }
            }

            return $preformedSteps >= $achievement->required_steps;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param string $achievementClass
     * @param int $userId
     *
     * @return bool
     * @throws \Exception
     */
    public static function achieveByUser($achievementClass, $userId)
    {
        try {
            /** @var Achievement $achievement */
            $achievement = Achievement::getAchievementByClassNameAndUser($userId, $achievementClass);

            if (!$achievement) {
                return false;
            }

            $userAchievement = UserAchievement::getUserAchievementByID($achievementClass, $achievement->id, $userId);

            $userAchievement->updateAttributes([
                'done' => DefUserAchievement::IS_DONE,
                'done_at' => new Expression('NOW()'),
            ]);

            $awardEvent = new AwardEvent;
            $awardEvent->objectId = $userAchievement->achievement_id;
            $awardEvent->userId = $userId;
            $awardEvent->senderClassName = static::className();

            AchievementHelper::achievementPassedUserNotification($achievement);

            static::onAchieved($awardEvent);

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param AwardEvent $event
     */
    protected static function onAchieved($event)
    {
        Yii::$app->trigger(self::EVENT_ACHIEVED, $event);
    }
}
