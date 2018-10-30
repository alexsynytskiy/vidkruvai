<?php

namespace app\controllers;

use app\components\Controller;
use app\components\helpers\StartBlock;
use app\models\Question;
use app\models\Test;
use app\models\UserAnswer;
use yii\captcha\CaptchaAction;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * Class TasksController
 * @package app\controllers
 */
class TasksController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => CaptchaAction::className(),
                'height' => 60,
                'maxLength' => 4,
                'minLength' => 4,
            ],
        ];
    }

    /**
     * @param string $hash
     * @return bool|string|\yii\web\Response
     */
    public function actionTest($hash)
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        \Yii::$app->seo->setTitle('Тест');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        /** @var Test $test */
        $test = Test::findOne(['hash' => $hash]);

        if ($test) {
            $testQuestionsCount = count($test->questions);
            $currentTime = time();
            $answersCount = 0;

            /** @var UserAnswer $answer */
            $userAnswers = $test->getUserAnswers();

            foreach ($userAnswers as $answer) {
                if ($answer->answer_id) {
                    $answersCount++;
                }
            }

            if ($answersCount < $testQuestionsCount &&
                $currentTime >= strtotime($test->starting_at) && $currentTime <= strtotime($test->ending_at)) {
                $test->active = Test::ACTIVE;
            } elseif (($answersCount > 1 && $currentTime > strtotime($test->ending_at)) || $answersCount === $testQuestionsCount) {
                $test->active = Test::ANSWERED;
            } elseif ($answersCount === 0 && $currentTime > strtotime($test->ending_at)) {
                $test->active = Test::MISSED;
            }

            return $this->render('test', [
                'test' => $test,
            ]);
        }

        return $this->redirect('/tasks');
    }

    /**
     * @param string $hash
     *
     * @return bool|string|\yii\web\Response
     *
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionAnswer($hash)
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        \Yii::$app->seo->setTitle('Відповіді');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        $test = Test::findOne(['hash' => $hash]);

        if ($test) {
            \Yii::$app->seo->setTitle('Блок питань ' . $test->name);

            /** @var UserAnswer[] $userAnswers */
            $userAnswers = UserAnswer::find()
                ->alias('qa')
                ->innerJoin(Question::tableName() . ' q', 'qa.question_id = q.id')
                ->where([
                    'qa.user_id' => \Yii::$app->siteUser->id,
                    'qa.answer_id' => null,
                    'qa.started_at' => null,
                    'q.group_id' => $test->id,
                ])
                ->all();

            if ($userAnswers) {
                StartBlock::startBlockLogic($hash);
            }

            $currentTime = time();

            if ($currentTime >= strtotime($test->starting_at) && $currentTime <= strtotime($test->ending_at)) {
                $test->active = Test::ACTIVE;
            }

            $blockQuestion = Question::findNextQuestion($test->id);
        } else {
            throw new Exception('Такого тесту не існує');
        }

        if (!$blockQuestion) {
            return $this->redirect('/profile');
        }

        return $this->render('answer', ['blockQuestion' => $blockQuestion]);
    }

    /**
     * @param string $hash
     * @return bool|string|\yii\web\Response
     */
    public function actionBlockFinished($hash)
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        $test = Test::findOne(['hash' => $hash]);

        if ($test) {
            \Yii::$app->seo->setTitle('Тест ' . $test->name . ' завершено');

            $userAnswers = $test->userAnswers;
            $answersCount = 0;
            $wrongAnswers = [];

            foreach ($userAnswers as $answer) {
                if ($answer->question->group_id === $test->id && $answer->answer_id) {
                    $answersCount++;
                }
            }

            if ($answersCount === Test::USER_BLOCK_QUESTIONS) {
                foreach ($userAnswers as $userAnswer) {
                    if (!$userAnswer->answer->is_correct) {
                        $wrongAnswers[] = $userAnswer->question->correct_answer;
                    }
                }

                $params = ['wrongAnswers' => $wrongAnswers, 'test' => $test];
            } else {
                return $this->redirect('/tasks');
            }
        }

        return $this->render('block-finished', $params);
    }
}