<?php

namespace app\components\helpers;

use app\models\definitions\DefTask;
use app\models\Question;
use app\models\Task;
use app\models\Test;
use app\models\TeamAnswer;

/**
 * Class StartBlock
 * @package app\components\helpers
 */
class StartBlock
{
    /**
     * @param string $hash
     * @return array
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function startBlockLogic($hash)
    {
        /** @var Test $test */
        $test = Test::find()
            ->alias('test')
            ->innerJoin(Task::tableName() . ' task', 'test.id = task.item_id')
            ->where(['task.hash' => $hash, 'task.item_type' => DefTask::TYPE_TEST])
            ->one();

        if ($test) {
            /** @var TeamAnswer[] $teamAnswers */
            $teamAnswers = TeamAnswer::find()
                ->alias('qa')
                ->innerJoin(Question::tableName() . ' q', 'qa.question_id = q.id')
                ->where([
                    'qa.team_id' => \Yii::$app->siteUser->identity->team->id,
                    'qa.answer_id' => null,
                    'q.group_id' => $test->id,
                ])
                ->all();

            $alreadyStarted = false;

            foreach ($teamAnswers as $answer) {
                if ($answer->started_at === null) {
                    $answer->started_at = date('Y-m-d H:i:s');
                } elseif ($answer->started_at !== null &&
                    ($answer->answered_at === null || $answer->answer_id === null) &&
                    strtotime($answer->started_at) + Question::TIME_FOR_ANSWER < time()) {
                    $answer->answer_id = -1;
                } elseif ($answer->started_at !== null && $answer->answered_at === null) {
                    $alreadyStarted = true;
                }

                $answer->update();
            }

            return [
                'status' => !$alreadyStarted ? 'success' : 'warning',
                'message' => !$alreadyStarted ? 'Відлік часу розпочато!' : 'Ти втрачаєш час, таймер не чекатиме',
                'answerBlockUrl' => '/tasks/test-answer/' . $hash,
            ];
        }

        return ['status' => 'error', 'message' => 'Тест не знайдено'];
    }
}
