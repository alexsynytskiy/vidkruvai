<?php

namespace app\components;

use app\components\achievement\PassedLevel;
use app\components\achievement\ReachedExperience;
use app\components\events\AwardEvent;
use app\models\Level;
use app\models\SiteUser;
use app\models\UserLevelHistory;
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
     * @param int $userId
     * @param int $addExperience
     *
     * @return bool
     * @throws \Exception
     */
    public static function addUserExperience($userId, $addExperience)
    {
        $user = static::getUser($userId);

        try {
            $levelsData = static::prepareExperience($user, $addExperience);
            static::addUserExperienceInternal($user, $addExperience, $levelsData);

            if (!AchievementComponent::getLastAchievement() && AchievementComponent::isGoalAchieved(
                ReachedExperience::CLASS_NAME, $userId)) {
                AchievementComponent::achieveByUser(ReachedExperience::CLASS_NAME, $userId);
            }

            return true;
        } catch (\Exception $e) {

            throw $e;
        }
    }

    /**
     * @param SiteUser $user
     * @param int $addExperience
     *
     * @return array
     */
    protected static function prepareExperience($user, $addExperience)
    {
        $level = $user->level;
        $currentLevelExp = $user->level_experience;
        $userExp = $user->total_experience + $addExperience;
        $passedLevels = Level::getPassedLevels($user->total_experience, $userExp);

        $result = [
            'level' => [],
            'levels' => [],
        ];

        $prevLevel = null;

        if (isset($passedLevels[0]['required_experience'])) {
            $prevLevel = Level::getPrevLevelByExp($passedLevels[0]['required_experience']);
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

            $levelExperience = $userExp - $passedLevel['required_experience'];

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
     * @param int $userId
     * @param array $levels
     * @throws \Exception
     */
    protected static function unlockLevels($userId, array $levels)
    {
        foreach ($levels as $level) {
            $triggerEvent = false;

            //If we do not found the level, it means that it still is not unlocked, therefore we must
            //write history (unlock level) and trigger the event that will give awards (if any)
            if (!UserLevelHistory::isLevelUnlocked($userId, $level['levelId'])) {
                $triggerEvent = true;
            }

            UserLevelHistory::logHistory($userId, $level);

            //Trigger event only when the level is unlocked at first time
            if ($triggerEvent) {
                $awardEvent = new AwardEvent();
                $awardEvent->objectId = $level['levelId'];
                $awardEvent->userId = $userId;
                $awardEvent->senderClassName = static::className();

                static::onUnlocked($awardEvent);

                if (!AchievementComponent::getLastAchievement() && AchievementComponent::isGoalAchieved(
                    PassedLevel::CLASS_NAME, $userId)) {
                    AchievementComponent::achieveByUser(PassedLevel::CLASS_NAME, $userId);
                }
            }
        }
    }

    /**
     * @param SiteUser $user
     * @param int $addExperience
     * @param array $levels
     * @throws \Exception
     */
    protected static function addUserExperienceInternal($user, $addExperience, array $levels)
    {
        $level = $levels['level'];

        $user->total_experience += $addExperience;
        $user->level_experience = $level['levelExp'];
        $user->level_id = $level['levelId'];
        $user->save(false);

        if ($levels['levels']) {
            static::unlockLevels($user->id, $levels['levels']);
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
