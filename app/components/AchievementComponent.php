<?php

namespace app\components;

use app\components\events\AwardEvent;
use app\models\Achievement;
use app\models\definitions\DefAchievements;
use app\models\definitions\DefEntityAchievement;
use app\models\SiteUser;
use app\models\EntityAchievement;
use app\models\Team;
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
     * @param       $entityId
     * @param       $entityType
     * @param array $params
     *
     * @return bool
     * @throws \Exception
     */
    public static function isGoalAchieved($achievementClass, $entityId, $entityType, $params = [])
    {
        try {
            /** @var SiteUser|Team $entity */
            $entity = null;

            if($entityType === DefEntityAchievement::ENTITY_USER) {
                $entity = SiteUser::find()->where(['id' => $entityId])->one();
            }
            elseif ($entityType === DefEntityAchievement::ENTITY_TEAM) {
                $entity = Team::find()->where(['id' => $entityId])->one();
            }

            if (!$entity) {
                return false;
            }

            /** @var Achievement $achievement */
            $achievement = Achievement::getAchievementByClassNameAndEntity($entityId, $entityType, $achievementClass);

            if (!$achievement) {
                return false;
            }

            $entityAchievement = EntityAchievement::getEntityAchievementByID($achievementClass, $achievement->id,
                $entityId, $entityType);

            if ($entityAchievement->is_first) {
                static::$isLastAchievement = false;

                return false;
            }

            if ($entityAchievement !== null && $entityAchievement->done) {
                return false;
            }

            if (!isset($params['entityId'])) {
                $params['entityId'] = $entityId;
            }

            if (!isset($params['entityType'])) {
                $params['entityType'] = $entityType;
            }

            if (!isset($params['required_steps'])) {
                $params['required_steps'] = $achievement->required_steps;
            }

            $preformedSteps = call_user_func(DefAchievements::NAMESPACE_RULES . $achievementClass . '::execute', $params);

            if ($preformedSteps === true) {
                static::$isLastAchievement = true;
                $entityAchievement->updateAttributes(['performed_steps' => $achievement->required_steps]);

                /** @var Achievement $firstAchievement */
                $firstAchievement = Achievement::getAchievementToIncrease($entityId, $entityType, $achievementClass, 'LEFT JOIN');
                $firstEntityAchievement = EntityAchievement::getEntityAchievementByID($achievementClass, $firstAchievement->id, $entityId, $entityType);
                $firstEntityAchievement->updateAttributes(['is_first' => 1]);

                return true;
            }

            $entityAchievement->updateAttributes(['performed_steps' => $preformedSteps]);

            if (array_key_exists('updateAchievementsGroup', $params) && $params['updateAchievementsGroup']) {
                $achievementsToUpdate = Achievement::getAchievementsToUpdatePerformedSteps($achievementClass, $achievement->id, $entityType);

                /** @var Achievement $item */
                foreach ($achievementsToUpdate as $item) {
                    /** @var EntityAchievement $userStatus */
                    $entityStatus = EntityAchievement::getEntityAchievementByID($item->class_name, $item->id, $entityId, $entityType);
                    $entityStatus->updateAttributes(['performed_steps' => $preformedSteps]);
                }
            }

            return $preformedSteps >= $achievement->required_steps;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param string $achievementClass
     * @param int $entityId
     * @param string $entityType
     *
     * @return bool
     * @throws \Exception
     */
    public static function achieveByUser($achievementClass, $entityId, $entityType)
    {
        try {
            /** @var Achievement $achievement */
            $achievement = Achievement::getAchievementByClassNameAndEntity($entityId, $entityType, $achievementClass);

            if (!$achievement) {
                return false;
            }

            $entityAchievement = EntityAchievement::getEntityAchievementByID($achievementClass, $achievement->id, $entityId, $entityType);

            $entityAchievement->updateAttributes([
                'done' => DefEntityAchievement::IS_DONE,
                'done_at' => new Expression('NOW()'),
            ]);

            $awardEvent = new AwardEvent;
            $awardEvent->objectId = $entityAchievement->achievement_id;
            $awardEvent->entityId = $entityId;
            $awardEvent->entityType = $entityType;
            $awardEvent->senderClassName = static::className();

            AchievementHelper::achievementPassedNotification($achievement);

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
