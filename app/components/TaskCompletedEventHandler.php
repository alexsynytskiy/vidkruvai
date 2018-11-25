<?php

namespace app\components;

use app\components\events\TaskCompletedEvent;
use app\models\definitions\DefEntityAchievement;
use yii\base\Component;
use yii\web\BadRequestHttpException;

/**
 * Class TaskCompletedEventHandler
 * @package app\components
 */
class TaskCompletedEventHandler extends Component
{
    /**
     * @var TaskCompletedEvent
     */
    protected $event;

    /**
     * @param TaskCompletedEvent $event
     * @throws \Exception
     */
    public function handle($event)
    {
        $this->event = $event;

        $this->checkAchievements();
    }

    /**
     * @throws \Exception
     */
    protected function checkAchievements()
    {
        if ($this->event !== null) {
            TaskComponent::taskAchievementsPass($this->event->taskId, $this->event->teamId,
                DefEntityAchievement::ENTITY_TEAM);
        } else {
            throw new BadRequestHttpException('Bad request');
        }
    }
}
