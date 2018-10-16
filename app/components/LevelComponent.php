<?php

namespace app\components;

use app\components\achievement\PassedLevel;
use app\components\achievement\ReachedExperience;
use app\components\events\AwardEvent;
use app\models\definitions\DefEntityAchievement;
use app\models\EntityLevelHistory;
use app\models\Level;
use app\models\SiteUser;
use app\models\Team;
use Yii;
use yii\base\Component;

/**
 * Class LevelComponent
 * @package app\components
 */
class LevelComponent extends Component
{
    /**
     * Event name, uses when triggers unlock event
     */
    const EVENT_UNLOCKED = 'app.components.LevelComponent.on-unlocked';
    /**
     * @var int
     */
    protected static $transactionId;

    /**
     * @param $userId
     *
     * @return SiteUser
     * @throws \Exception
     */
    protected static function getUser($userId)
    {
        /** @var SiteUser $user */
        $user = SiteUser::find()->with('level')->where(['id' => $userId])->one();

        if (!$user) {
            throw new \Exception("User ({$userId}) is not found.");
        }

        return $user;
    }

    /**
     * @param int $teamId
     *
     * @return Team
     * @throws \Exception
     */
    protected static function getTeam($teamId)
    {
        /** @var Team $team */
        $team = Team::find()->with('level')->where(['id' => $teamId])->one();

        if (!$team) {
            throw new \Exception("Team ({$team}) is not found.");
        }

        return $team;
    }

    /**
     * @param int $entityId
     * @param string $entityType
     * @param int $addExperience
     *
     * @return bool
     * @throws \Exception
     */
    public static function addEntityExperience($entityId, $entityType, $addExperience)
    {
        $entity = null;

        if ($entityType === DefEntityAchievement::ENTITY_TEAM) {
            $entity = static::getTeam($entityId);
        } elseif ($entityType === DefEntityAchievement::ENTITY_USER) {
            $entity = static::getUser($entityId);
        }

        try {
            $levelsData = static::prepareExperience($entity, $addExperience, $entityType);
            static::addEntityExperienceInternal($entity, $entityType, $addExperience, $levelsData);

            if (!AchievementComponent::getLastAchievement() && AchievementComponent::isGoalAchieved(
                    ReachedExperience::CLASS_NAME, $entityId, $entityType, ['updateAchievementsGroup' => true])) {
                AchievementComponent::achieveByUser(ReachedExperience::CLASS_NAME, $entityId, $entityType);
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param SiteUser|Team $entity
     * @param int $addExperience
     * @param string $entityType
     *
     * @return array
     */
    protected static function prepareExperience($entity, $addExperience, $entityType)
    {
        $level = $entity->level;
        $currentLevelExp = $entity->level_experience;
        $entityExp = $entity->total_experience + $addExperience;
        $passedLevels = Level::getPassedLevels($entity->total_experience, $entityExp, $entityType);

        $result = [
            'level' => [],
            'levels' => [],
        ];

        $prevLevel = null;

        if (isset($passedLevels[0]['required_experience'])) {
            $prevLevel = Level::getPrevLevelByExp($passedLevels[0]['required_experience'], $entityType);
        }

        foreach ($passedLevels as $passedLevel) {
            //0 required experience can be only on the first level, so - skip it
            if ($passedLevel['required_experience'] === 0) {
                continue;
            }

            //How many experience is allowed on the level
            $levelAllowedExp = $passedLevel['required_experience'] - $prevLevel['required_experience'];

            $addedExp = $levelAllowedExp - $currentLevelExp;

            if ($addedExp === 0) {
                $addedExp = $levelAllowedExp;
            }

            $levelExperience = $entityExp - $passedLevel['required_experience'];

            $result['levels'][] = [
                'levelId' => $passedLevel['id'],
                'maxExp' => $passedLevel['required_experience'],
                'addedExp' => $addedExp,
                'levelExp' => $levelExperience,
            ];

            $currentLevelExp = $levelAllowedExp;
            $prevLevel = $passedLevel;
        }

        if ($result['levels']) {
            $currentLevel = array_slice($result['levels'], -1)[0];

            if ($currentLevel['levelExp'] > 0) {
                $currentLevel['addedExp'] = $currentLevel['levelExp'];

                $result['levels'][] = $currentLevel;
            }

            $result['level'] = $currentLevel;
        } else {
            $result['level'] = [
                'levelId' => $level->id,
                'maxExp' => $level->required_experience,
                'addedExp' => $addExperience,
                'levelExp' => $currentLevelExp + $addExperience,
            ];

            $result['levels'][] = $result['level'];
        }

        return $result;
    }

    /**
     * @param int $entityId
     * @param string $entityType
     * @param array $levels
     *
     * @throws \Exception
     */
    protected static function unlockLevels($entityId, $entityType, array $levels)
    {
        foreach ($levels as $level) {
            $triggerEvent = false;

            //If we do not found the level, it means that it still is not unlocked, therefore we must
            //write history (unlock level) and trigger the event that will give awards (if any)
            if (!EntityLevelHistory::isLevelUnlocked($entityId, $entityType, $level['levelId'])) {
                $triggerEvent = true;
            }

            EntityLevelHistory::logHistory($entityId, $entityType, $level);

            //Trigger event only when the level is unlocked at first time
            if ($triggerEvent) {
                $awardEvent = new AwardEvent();
                $awardEvent->objectId = $level['levelId'];
                $awardEvent->entityId = $entityId;
                $awardEvent->entityType = $entityType;
                $awardEvent->senderClassName = static::className();

                static::onUnlocked($awardEvent);

                if (!AchievementComponent::getLastAchievement() && AchievementComponent::isGoalAchieved(
                        PassedLevel::CLASS_NAME, $entityId, $entityType)) {
                    AchievementComponent::achieveByUser(PassedLevel::CLASS_NAME, $entityId, $entityType);
                }
            }
        }
    }

    /**
     * @param SiteUser|Team $entity
     * @param string $entityType
     * @param int $addExperience
     * @param array $levels
     * @throws \Exception
     */
    protected static function addEntityExperienceInternal($entity, $entityType, $addExperience, array $levels)
    {
        $level = $levels['level'];

        $entity->total_experience += $addExperience;
        $entity->level_experience = $level['levelExp'];
        $entity->level_id = $level['levelId'];
        $entity->save(false);

        if ($levels['levels']) {
            static::unlockLevels($entity->id, $entityType, $levels['levels']);
        }
    }

    /**
     * @param AwardEvent $event
     */
    protected static function onUnlocked($event)
    {
        Yii::$app->trigger(self::EVENT_UNLOCKED, $event);
    }
}
