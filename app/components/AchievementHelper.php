<?php

namespace app\components;

use app\models\Achievement;
use app\models\Award;
use app\models\Category;
use app\models\definitions\DefEntityAchievement;
use app\models\Level;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class AchievementHelper
 * @package app\components
 */
class AchievementHelper
{
    /**
     * @param $message
     *
     * @return bool
     */
    public static function setFlashArray($message)
    {
        if (!isset(Yii::$app->session)) {
            return false;
        }

        $newFlash = [];
        $previousFlash = Yii::$app->session->getFlash('success');

        if (!is_array($previousFlash)) {
            if ($previousFlash) {
                $newFlash[] = $previousFlash;
            }
            $newFlash[] = $message;
        } else {
            $newFlash = array_merge($previousFlash, [$message]);
        }

        Yii::$app->session->setFlash('success', $newFlash);
    }

    /**
     * @param Achievement $achievement
     */
    public static function achievementPassedNotification($achievement)
    {
        $message = $achievement->entity_type === DefEntityAchievement::ENTITY_USER ?
            'Вітаємо! Ви виконали досягнення {achievementName}' : 'Вітаємо! Команда виконала досягнення {achievementName}';
        $msgParams = ['achievementName' => Html::a(Html::encode($achievement->name),
            $achievement->entity_type === DefEntityAchievement::ENTITY_USER ? '/profile/achievements' : '/team/achievements')];

        if (count($achievement->awards) > 0) {
            $message = $achievement->entity_type === DefEntityAchievement::ENTITY_USER ?
                'Вітаємо! Ви виконали досягнення {achievementName} та отримали <span class="bold">{awards}</span>' :
                'Вітаємо! Команда виконала досягнення {achievementName} та отримала <span class="bold">{awards}</span>';
            $awardsNames = '';

            foreach ($achievement->awards as $award) {
                $awardsNames .= $award->name . '</span>, ';
            }

            $awardsNames = rtrim($awardsNames, ', ');

            $msgParams['awards'] = $awardsNames;
        }

        static::setFlashArray(AppMsg::t($message, $msgParams));
    }

    /**
     * @param int $levelId
     * @param Award[] $awards
     * @param string $entityType
     */
    public static function levelPassedNotification($levelId, $awards, $entityType)
    {
        $levelNum = (new Query)
            ->select('l.num')
            ->from(Level::tableName() . ' l')
            ->where(['id' => $levelId, 'entity_type' => $entityType])
            ->scalar();

        $message = $entityType === DefEntityAchievement::ENTITY_USER ? 'Ви перейшли на {levelLink}' :
            'Команда перейшла на {levelLink}';
        $msgParams = [
            'levelLink' => Html::a(AppMsg::t('рівень {levelNum}', ['levelNum' => $levelNum]),
                $entityType === DefEntityAchievement::ENTITY_USER ? '/profile/levels' : '/team/levels', ['class' => 'bold']),
        ];

        if (count($awards) > 0) {
            $message = $entityType === DefEntityAchievement::ENTITY_USER ?
                'Ви перейшли на {levelLink} та отримали <span class="bold">{awards}</span>' :
                'Команда перейшла на {levelLink} та отримала <span class="bold">{awards}</span>';
            $awardsNames = '';

            foreach ($awards as $award) {
                $awardsNames .= $award->name . ', ';
            }

            $awardsNames = rtrim($awardsNames, ', ');

            $msgParams['awards'] = $awardsNames;
        }

        static::setFlashArray(AppMsg::t($message, $msgParams));
    }

    /**
     * @param int $awardValue
     * @param int $current
     * @param string $class
     * @param string $entityType
     *
     * @return array
     */
    public static function getTransactionsDividedAward($awardValue, $current, $class, $entityType)
    {
        $expected = $current + $awardValue;

        $selectRule = (new Query())
            ->select('a.required_steps')
            ->from(Achievement::tableName() . ' a')
            ->where([
                'a.archived' => Achievement::IS_NOT_ARCHIVED,
                'a.class_name' => $class,
                'entity_type' => $entityType,
            ])
            ->orderBy('a.required_steps ASC');

        $selectRuleDone = clone $selectRule;
        $selectRuleFuture = clone $selectRule;

        $queryDoneAchievements = $selectRuleDone
            ->andFilterWhere(['<', 'a.required_steps', $expected])
            ->andFilterWhere(['>', 'a.required_steps', $current])
            ->all();

        $queryLastAchievement = $selectRuleFuture
            ->andFilterWhere(['>', 'a.required_steps', $expected])
            ->one();

        $achievementsDone = $queryDoneAchievements ?
            ArrayHelper::getColumn($queryDoneAchievements, 'required_steps') : [];
        $achievementsCurrent = ArrayHelper::getColumn((array)$queryLastAchievement, 'required_steps');

        $achievements = array_merge($achievementsDone, $achievementsCurrent);
        $transactions = [];
        $countAchievements = count($achievements);

        for ($i = 0; $i < $countAchievements; $i++) {
            if ($i === 0 && count($achievementsDone) > 0) {
                $transactions[] = $achievements[$i] - $current;
                $awardValue -= $achievements[$i] - $current;
            } elseif ($i < count($achievements) - 1 && count($achievementsDone)) {
                $transactions[] = $achievements[$i] - $achievements[$i - 1];
                $awardValue -= $achievements[$i] - $achievements[$i - 1];
            } else {
                $transactions[] = $awardValue;
            }
        }

        return $transactions;
    }
}
