<?php

namespace app\components\helpers;

use app\models\Test;
use app\models\UserAnswer;
use yii\db\Exception;

/**
 * Class QuestionsSetter
 * @package app\components\helpers
 */
class QuestionsSetter
{
    /**
     * @throws Exception
     */
    public static function setUserQuestions()
    {
        $userId = \Yii::$app->siteUser->identity->id;

        $questionGroups = Test::find()->orderBy('id')->all();

        /** @var Test $group */
        foreach ($questionGroups as $group) {
            $groupQuestions = $group->questions;

            $questionNumber1 = 0;
            $questionNumber2 = 1;

            if (count($groupQuestions) > Test::USER_BLOCK_QUESTIONS) {
                $questionNumber1 = mt_rand(0, count($groupQuestions) - 1);
                do {
                    $questionNumber2 = mt_rand(0, count($groupQuestions) - 1);
                } while ($questionNumber1 === $questionNumber2);
            }

            $questionPositions = [$questionNumber1, $questionNumber2];

            foreach ($questionPositions as $position) {
                $answer = new UserAnswer();
                $answer->site_user_id = $userId;
                $answer->question_id = $groupQuestions[$position]->id;

                if (!$answer->save()) {
                    throw new Exception('User questions answers not created');
                }
            }
        }
    }
}
