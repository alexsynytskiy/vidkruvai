<?php

namespace app\components\helpers;

use app\models\Test;
use app\models\TeamAnswer;
use yii\db\Exception;

/**
 * Class QuestionsSetter
 * @package app\components\helpers
 */
class QuestionsSetter
{
    /**
     * @param int $teamId
     * @param int $testId
     * @throws Exception
     */
    public static function setTeamQuestions($teamId, $testId)
    {
        /** @var Test $test */
        $test = Test::find()->where(['id' => $testId])->one();

        $testQuestions = $test->questions;
        $testQuestionsCount = count($test->teamAnswers);

        $selectedStack = [];

        if (count($testQuestions) > $testQuestionsCount) {
            $selectedStack[] = mt_rand(0, count($testQuestions) - 1);
            do {
                $newNumber = mt_rand(0, count($testQuestions) - 1);

                if(!in_array($newNumber, $selectedStack,false)) {
                    $selectedStack[] = $newNumber;
                }
            } while (count($selectedStack) < count($testQuestions));
        }

        foreach ($selectedStack as $position) {
            $answer = new TeamAnswer();
            $answer->team_id = $teamId;
            $answer->question_id = $testQuestions[$position]->id;

            if (!$answer->save()) {
                throw new Exception('User questions answers not created');
            }
        }
    }
}
