<?php

namespace app\components;

use app\components\achievement\RegistrationAchievement;
use app\components\events\UserRegisteredEvent;
use yii\web\BadRequestHttpException;

/**
 * Class UserRegisteredEventHandler
 * @package app\components
 */
class UserRegisteredEventHandler
{
    /**
     * @var UserRegisteredEvent
     */
    protected $event;

    /**
     * @param UserRegisteredEvent $event
     * @throws BadRequestHttpException
     * @throws \Exception
     */
    public function handle($event)
    {
        $this->event = $event;

        $this->checkUserAccounts();
    }

    /**
     * @throws \Exception
     * @throws \yii\web\BadRequestHttpException
     */
    protected function checkUserAccounts()
    {
        if ($this->event !== null) {
            if (AchievementComponent::isGoalAchieved(RegistrationAchievement::CLASS_NAME, $this->event->userId)) {
                AchievementComponent::achieveByUser(RegistrationAchievement::CLASS_NAME, $this->event->userId);
            }
        } else {
            throw new BadRequestHttpException('Bad request');
        }
    }
}
