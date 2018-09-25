<?php

namespace app\components;

use app\components\events\AwardEvent;
use app\models\Achievement;
use app\models\Award;
use app\models\definitions\DefAward;
use app\models\definitions\DefUserAward;
use app\models\Level;
use app\models\UserAward;
use Yii;

/**
 * Class AwardEventHandler
 * @package app\components
 */
class AwardEventHandler
{
    /**
     * @var AwardEvent
     */
    protected $event;
    /**
     * @var Award[] array
     */
    protected $awards = [];
    /**
     * @var string
     */
    protected $awardType;

    /**
     * @param AwardEvent $event
     * @throws \Exception
     */
    public function award($event)
    {
        $this->event = $event;

        $this->initParams();
        $this->giveAwards();

        Yii::getLogger()->flush(true);
    }

    /**
     * @return void
     */
    protected function initParams()
    {
        if ($this->event->senderClassName === AchievementComponent::className()) {
            $this->awards = Achievement::getListAwards($this->event->objectId);
            $this->awardType = DefUserAward::TYPE_ACHIEVEMENT;
        } elseif ($this->event->senderClassName === LevelComponent::className()) {
            $this->awards = Level::getListAwards($this->event->objectId);
            AchievementHelper::levelPassedUserNotification($this->event->objectId, $this->awards);
            $this->awardType = DefUserAward::TYPE_LEVEL;
        }
    }

    /**
     * @throws \Exception
     */
    protected function giveAwards()
    {
        foreach ($this->awards as $award) {
            if ($award->type === DefAward::TYPE_EXPERIENCE) {
                $this->giveExperience($award);
            }

            try {
                $awardLog = new UserAward();
                $awardLog->site_user_id = $this->event->userId;
                $awardLog->award_id = $award->id;
                $awardLog->type = $this->awardType;
                $awardLog->object_id = $this->event->objectId ?: null; //Id of element, after achievement which award was taken(DefLandingUserAward::listTypes)
                $awardLog->save(false);
            } catch (\Throwable $e) {

            }
        }
    }

    /**
     * @param Award $award
     * @param null $awardValue
     *
     * @return bool
     * @throws \Exception
     */
    protected function giveExperience($award, $awardValue = null)
    {
        $value = $awardValue ? $awardValue : $award->value;
        LevelComponent::addUserExperience($this->event->userId, $value);

        return true;
    }
}
