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

        $tests = Test::find()->orderBy('id')->all();

        /** @var Test $test */
        foreach ($tests as $test) {
            $testQuestions = $test->questions;
            $testQuestionsCount = count($testQuestions);

            $selectedStack = [];

            if (count($testQuestions) > $testQuestionsCount) {
                $selectedStack[] = mt_rand(0, count($testQuestions) - 1);
                do {
                    $newNumber = mt_rand(0, count($testQuestions) - 1);

                    if(!in_array($newNumber, $selectedStack,false)) {
                        $selectedStack[] = $newNumber;
                    }
                } while (count($selectedStack) < $testQuestionsCount);
            }

            foreach ($selectedStack as $position) {
                $answer = new UserAnswer();
                $answer->site_user_id = $userId;
                $answer->question_id = $testQuestions[$position]->id;

                if (!$answer->save()) {
                    throw new Exception('User questions answers not created');
                }
            }
        }
    }
}
