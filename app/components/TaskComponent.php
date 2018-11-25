<?php

namespace app\components;

use app\components\events\AwardEvent;
use Yii;
use yii\base\Component;

/**
 * Class TaskComponent
 * @package app\components
 */
class TaskComponent extends Component
{

    /**
     * Event name, uses when triggers achieve event
     */
    const EVENT_ACHIEVED = 'app.components.TaskComponent.on-executed';

    /**
     * @param int $taskId
     * @param int $teamId
     * @param string $entityType
     * @return bool
     * @throws \Exception
     */
    public static function taskAchievementsPass($taskId, $teamId, $entityType)
    {
        try {
            $awardEvent = new AwardEvent;
            $awardEvent->objectId = $taskId;
            $awardEvent->entityId = $teamId;
            $awardEvent->entityType = $entityType;
            $awardEvent->senderClassName = static::className();

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
