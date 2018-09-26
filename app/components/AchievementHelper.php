<?php

namespace app\components;

use app\models\Achievement;
use app\models\Award;
use app\models\Category;
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
    public static function achievementPassedUserNotification($achievement)
    {
        $message = 'Поздраляем! Вы выполнили достижение {achievementName}';
        $msgParams = ['achievementName' => Html::a(Html::encode($achievement->name), '/profile')];

        if (count($achievement->awards) > 0) {
            $message = 'Поздраляем! Вы выполнили достижение {achievementName} и получили <span class="bold">{awards}</span>';
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
     */
    public static function levelPassedUserNotification($levelId, $awards)
    {
        $levelNum = (new Query)
            ->select('l.num')
            ->from(Level::tableName() . ' l')
            ->where(['id' => $levelId])
            ->scalar();

        $message = 'Вы перешли на {levelLink}';
        $msgParams = [
            'levelLink' => Html::a(AppMsg::t('уровень {levelNum}', ['levelNum' => $levelNum]),
                '/profile/levels', ['class' => 'bold']),
        ];

        if (count($awards) > 0) {
            $message = 'Вы перешли на {levelLink} и получили <span class="bold">{awards}</span>';
            $awardsNames = '';

            foreach ($awards as $award) {
                $awardsNames .= $award->name . ', ';
            }

            $awardsNames = rtrim($awardsNames, ', ');

            $msgParams['awards'] = $awardsNames;
        }

        AppMsg::t($message, $msgParams);

        static::setFlashArray($message);
    }

    /**
     * @param int $awardValue
     * @param int $current
     * @param string $class
     *
     * @return array
     */
    public static function getTransactionsDividedAward($awardValue, $current, $class)
    {
        $expected = $current + $awardValue;

        $selectRule = (new Query())
            ->select('a.required_steps')
            ->from(Achievement::tableName() . ' a')
            ->where([
                'a.archived' => Achievement::IS_NOT_ARCHIVED,
                'a.class_name' => $class,
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

        for ($i = 0; $i < count($achievements); $i++) {
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
