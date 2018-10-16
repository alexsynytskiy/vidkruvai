<?php

namespace app\components;

use app\components\achievement\RegistrationAchievement;
use app\components\events\TeamRegisteredEvent;
use app\components\events\UserRegisteredEvent;
use app\models\definitions\DefEntityAchievement;
use yii\web\BadRequestHttpException;

/**
 * Class TeamRegisteredEventHandler
 * @package app\components
 */
class TeamRegisteredEventHandler
{
    /**
     * @var TeamRegisteredEvent
     */
    protected $event;

    /**
     * @param TeamRegisteredEvent $event
     * @throws BadRequestHttpException
     * @throws \Exception
     */
    public function handle($event)
    {
        $this->event = $event;

        $this->checkTeam();
    }

    /**
     * @throws \Exception
     * @throws \yii\web\BadRequestHttpException
     */
    protected function checkTeam()
    {
        if ($this->event !== null) {
            if (AchievementComponent::isGoalAchieved(RegistrationAchievement::CLASS_NAME,
                $this->event->teamId, DefEntityAchievement::ENTITY_TEAM)) {
                AchievementComponent::achieveByUser(RegistrationAchievement::CLASS_NAME,
                    $this->event->teamId, DefEntityAchievement::ENTITY_TEAM);
            }
        } else {
            throw new BadRequestHttpException('Bad request');
        }
    }
}
