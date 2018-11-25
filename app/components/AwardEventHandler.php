<?php

namespace app\components;

use app\components\events\AwardEvent;
use app\models\Achievement;
use app\models\Award;
use app\models\definitions\DefAward;
use app\models\definitions\DefEntityAward;
use app\models\EntityAward;
use app\models\Level;
use app\models\Task;
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
            $this->awards = Achievement::getListAwards($this->event->objectId, $this->event->entityType);
            $this->awardType = DefEntityAward::TYPE_ACHIEVEMENT;
        } elseif ($this->event->senderClassName === LevelComponent::className()) {
            $this->awards = Level::getListAwards($this->event->objectId, $this->event->entityType);
            AchievementHelper::levelPassedNotification($this->event->objectId, $this->awards, $this->event->entityType);
            $this->awardType = DefEntityAward::TYPE_LEVEL;
        } elseif ($this->event->senderClassName === TaskComponent::className()) {
            $this->awards = Task::getListAwards($this->event->objectId);
            AchievementHelper::taskExecutedNotification($this->event->objectId, $this->awards);
            $this->awardType = DefEntityAward::TYPE_TASK;
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
                $awardLog = new EntityAward();
                $awardLog->entity_id = $this->event->entityId;
                $awardLog->entity_type = $this->event->entityType;
                $awardLog->award_id = $award->id;
                $awardLog->type = $this->awardType;
                $awardLog->object_id = $this->event->objectId ?: null;
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
        $value = $awardValue ?: $award->value;
        LevelComponent::addEntityExperience($this->event->entityId, $this->event->entityType, $value);

        return true;
    }
}
