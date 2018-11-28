<?php

namespace app\controllers;

use app\components\AppMsg;
use app\components\Controller;
use app\components\events\TaskCompletedEvent;
use app\components\helpers\EntityHelper;
use app\components\helpers\QuestionsSetter;
use app\components\helpers\StartBlock;
use app\components\TaskCompletedEventHandler;
use app\models\definitions\DefEntityAchievement;
use app\models\definitions\DefNotificationUser;
use app\models\definitions\DefTask;
use app\models\definitions\DefTeam;
use app\models\Question;
use app\models\Task;
use app\models\Test;
use app\models\TeamAnswer;
use app\models\WrittenTask;
use app\models\WrittenTaskAnswer;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use app\models\Answer;
use app\models\SiteUser;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class TasksController
 * @package app\controllers
 */
class TasksController extends Controller
{
    /**
     * Event name, uses for triggering event when team finished task
     */
    const EVENT_TASK_COMPLETED = 'app.controllers.on-task-completed';

    public function init()
    {
        parent::init();

        \Yii::$app->on(self::EVENT_TASK_COMPLETED, [new TaskCompletedEventHandler(), 'handle']);
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * @param string $category
     * @return bool|string|\yii\web\Response
     */
    public function actionIndex($category = '')
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        \Yii::$app->seo->setTitle('Завдання');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        $team = \Yii::$app->siteUser->identity->team;

        if(!$team) {
            $this->flash('error', AppMsg::t('Щоб виконувати завдання необхідно долучитись до команди!'));
            return $this->redirect('/profile');
        }

        if($team->status === DefTeam::STATUS_UNCONFIRMED) {
            $this->flash('error', AppMsg::t('Ми перевіряємо склад вашої команди та відповідність правилам! Зачекай трохи, та повертайся.'));
            return $this->redirect('/team');
        }

        if($team->status === DefTeam::STATUS_DISABLED) {
            $this->flash('error', AppMsg::t('На жаль склад вашої команди не відповідає правилам. У вас є час до 02.12.2018 запросити до команди учнів з класів, яких не вистачає.'));
            return $this->redirect('/team');
        }

        $hasToLoadMore = false;
        $lastItemId = 0;
        /** @var Task[] $tasks */
        $tasks = Task::find()
            ->where(['<=', 'starting_at', new Expression('NOW()')])
            ->andWhere(['>=', 'ending_at', new Expression('NOW()')])
            ->andWhere(['status' => DefTask::STATUS_ON])
            ->orderBy('starting_at, id DESC')
            ->all();

        foreach ($tasks as $task) {
            $currentTime = time();

            if($task->item_type === DefTask::TYPE_TEST) {
                $test = $task->object;
                $testQuestionsCount = count($test->questions);
                $answersCount = 0;
                $testTimeFailed = false;

                /** @var TeamAnswer $answer */
                $teamAnswers = $test->getTeamAnswers();

                foreach ($teamAnswers as $answer) {
                    if ($answer->answer_id) {
                        $answersCount++;
                    }

                    if($answer->answer_id !== -1) {
                        $testTimeFailed = true;
                        break;
                    }
                }

                if (!$testTimeFailed && $answersCount < $testQuestionsCount &&
                    $currentTime >= strtotime($test->task->starting_at) && $currentTime <= strtotime($test->task->ending_at)) {
                    $task->stateForTeam = Test::ACTIVE;
                } elseif ($answersCount === $testQuestionsCount) {
                    $task->stateForTeam = Test::ANSWERED;
                } elseif (($answersCount < $testQuestionsCount && $currentTime > strtotime($test->task->ending_at)) || $testTimeFailed) {
                    $task->stateForTeam = Test::MISSED;
                }
            }

            if($task->item_type === DefTask::TYPE_WRITTEN) {
                if ($team && !$task->object->teamAnswered($team->id)) {
                    if($currentTime >= strtotime($task->starting_at) && $currentTime <= strtotime($task->ending_at)) {
                        $task->stateForTeam = Test::ACTIVE;
                    }
                    if($currentTime > strtotime($task->ending_at)) {
                        $task->stateForTeam = Test::MISSED;
                    }
                } else {
                    $task->stateForTeam = Test::ANSWERED;
                }
            }
        }

        $params = ArrayHelper::merge(
            EntityHelper::getEntityCredentials(DefEntityAchievement::ENTITY_USER, \Yii::$app->siteUser->id),
            [
                'tasks' => $tasks,
                'hasToLoadMore' => $hasToLoadMore,
                'lastItemId' => $lastItemId,
                'category' => $category,
            ]
        );

        return $this->render('index', $params);
    }

    /**
     * @param string $hash
     * @return bool|string|Response
     * @throws Exception
     */
    public function actionTest($hash)
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        $team = \Yii::$app->siteUser->identity->team;

        if(!\Yii::$app->siteUser->identity->team) {
            $this->flash('error', AppMsg::t('Щоб виконувати завдання необхідно долучитись до команди!'));
            return $this->redirect('/profile');
        }

        /** @var Test $test */
        $test = Test::find()
            ->alias('test')
            ->innerJoin(Task::tableName() . ' task', 'task.item_id = test.id')
            ->where(['task.hash' => $hash, 'task.item_type' => DefTask::TYPE_TEST])
            ->one();

        if ($test) {
            \Yii::$app->seo->setTitle('Тест|' . $test->name);
            \Yii::$app->seo->setDescription('Відкривай Україну');
            \Yii::$app->seo->setKeywords('Відкривай, Україну');

            QuestionsSetter::setTeamQuestions($team->id, $test->id);
            Task::readByIds([$test->task->id]);

            $testQuestionsCount = count($test->questions);
            $currentTime = time();
            $answersCount = 0;

            /** @var TeamAnswer $answer */
            $teamAnswers = $test->getTeamAnswers();

            foreach ($teamAnswers as $answer) {
                if ($answer->answer_id) {
                    $answersCount++;
                }
            }

            if ($answersCount < $testQuestionsCount &&
                $currentTime >= strtotime($test->task->starting_at) && $currentTime <= strtotime($test->task->ending_at)) {
                $test->active = Test::ACTIVE;
            } elseif (($answersCount > 1 && $currentTime > strtotime($test->task->ending_at)) || $answersCount === $testQuestionsCount) {
                $test->active = Test::ANSWERED;
            } elseif ($answersCount === 0 && $currentTime > strtotime($test->task->ending_at)) {
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
     * @return array|bool|string|Response
     * @throws \Throwable
     */
    public function actionWritten($hash)
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        $team = \Yii::$app->siteUser->identity->team;

        if(!$team) {
            $this->flash('error', AppMsg::t('Щоб виконувати завдання необхідно долучитись до команди!'));
            return $this->redirect('/profile');
        }

        /** @var WrittenTask $writtenTask */
        $writtenTask = WrittenTask::find()
            ->alias('written')
            ->innerJoin(Task::tableName() . ' task', 'task.item_id = written.id')
            ->where(['task.hash' => $hash])
            ->one();

        if ($writtenTask) {
            \Yii::$app->seo->setTitle('Завдання|' . $writtenTask->name);
            \Yii::$app->seo->setDescription('Відкривай Україну');
            \Yii::$app->seo->setKeywords('Відкривай, Україну');

            Task::readByIds([$writtenTask->task->id]);

            $answer = WrittenTaskAnswer::findOne(['task_id' => $writtenTask->id, 'team_id' => $team->id]);

            if(!$answer) {
                $answer = new WrittenTaskAnswer;
                $answer->task_id = $writtenTask->id;
                $answer->team_id = $team->id;

                if(!$answer->save()) {
                    $this->flash('error', AppMsg::t('Помилка бази данних, спробуйте ще раз'));
                    return $this->refresh();
                }
            }

            if(time() > strtotime($writtenTask->task->ending_at)) {
                $this->flash('error', AppMsg::t('Час на відповідь скінчився.. На це завдання ваша команда надіслати відповідь не зможе. Наступного разу не повторюйте цю помилку!'));
                return $this->redirect(['/tasks']);
            }

            if (!$answer->text && $answer->load(\Yii::$app->request->post())) {
                if (\Yii::$app->request->isAjax) {
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($answer);
                }

                if ($answer->update()) {
                    $taskEvent = new TaskCompletedEvent;
                    $taskEvent->taskId = $writtenTask->task->id;
                    $taskEvent->teamId = $team->id;
                    \Yii::$app->trigger(self::EVENT_TASK_COMPLETED, $taskEvent);
                } else {
                    $this->flash('error', AppMsg::t('Помилка збереження відповіді! Спробуйте ще раз'));
                    return $this->refresh();
                }

                return $this->redirect(['/tasks']);
            }

            return $this->render('written', [
                'task' => $writtenTask,
                'answer' => $answer,
            ]);
        }

        $this->flash('error', AppMsg::t('Такого завдання не існує'));
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
    public function actionTestAnswer($hash)
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        \Yii::$app->seo->setTitle('Відповіді');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        if(!\Yii::$app->siteUser->identity->team) {
            $this->flash('error', AppMsg::t('Щоб виконувати завдання необхідно долучитись до команди!'));
            return $this->redirect('/profile');
        }

        /** @var Test $test */
        $test = Test::find()
            ->alias('test')
            ->innerJoin(Task::tableName() . ' task', 'task.item_id = test.id')
            ->where(['task.hash' => $hash, 'task.item_type' => DefTask::TYPE_TEST])
            ->one();

        if ($test) {
            \Yii::$app->seo->setTitle('Тест: ' . $test->name);

            /** @var TeamAnswer[] $teamAnswers */
            $teamAnswers = TeamAnswer::find()
                ->alias('qa')
                ->innerJoin(Question::tableName() . ' q', 'qa.question_id = q.id')
                ->where([
                    'qa.team_id' => \Yii::$app->siteUser->identity->team->id,
                    'qa.answer_id' => null,
                    'qa.started_at' => null,
                    'q.group_id' => $test->id,
                ])
                ->all();

            if ($teamAnswers) {
                StartBlock::startBlockLogic($hash);
            }

            $currentTime = time();

            if ($currentTime >= strtotime($test->task->starting_at) && $currentTime <= strtotime($test->task->ending_at)) {
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
    public function actionTestFinished($hash)
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        if(!\Yii::$app->siteUser->identity->team) {
            $this->flash('error', AppMsg::t('Щоб виконувати завдання необхідно долучитись до команди!'));
            return $this->redirect('/profile');
        }

        /** @var Test $test */
        $test = Test::find()
            ->alias('test')
            ->innerJoin(Task::tableName() . ' task', 'task.item_id = test.id')
            ->where(['task.hash' => $hash])
            ->one();

        $params = [];

        if ($test) {
            \Yii::$app->seo->setTitle('Тест ' . $test->name . ' завершено');

            $teamAnswers = $test->teamAnswers;
            $answersCount = 0;
            $wrongAnswers = [];

            foreach ($teamAnswers as $answer) {
                if ($answer->question->group_id === $test->id && $answer->answer_id) {
                    $answersCount++;
                }
            }

            if ($answersCount === count($teamAnswers)) {
                foreach ($teamAnswers as $teamAnswer) {
                    if (!$teamAnswer->answer->is_correct) {
                        $wrongAnswers[] = $teamAnswer->question->correct_answer;
                    }
                }

                $params = ['wrongAnswers' => $wrongAnswers, 'test' => $test];
            } else {
                return $this->redirect('/tasks');
            }
        }

        return $this->render('test-finished', $params);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function actionStartTest()
    {
        $errorResponse = ['status' => 'error', 'message' => 'Щось пішло не так..'];

        if (!\Yii::$app->mutex->acquire('multiple-start-test')) {
            \Yii::info('Пользователь попытался несколько начать тест');

            return $errorResponse;
        }

        try {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            if (!\Yii::$app->request->isPost || \Yii::$app->siteUser->isGuest) {
                throw new BadRequestHttpException();
            }

            $request = \Yii::$app->request;
            $token = $request->post(\Yii::$app->request->csrfParam, '');

            if (!\Yii::$app->request->validateCsrfToken($token)) {
                throw new BadRequestHttpException();
            }

            $userId = \Yii::$app->siteUser->identity->id;
            $user = SiteUser::findOne($userId);

            if ($user) {
                $hash = \Yii::$app->request->post('hash');
                return StartBlock::startBlockLogic($hash);
            }

            return ['status' => 'error', 'message' => 'Користувача не знайдено'];
        } catch (BadRequestHttpException $exception) {
            return $errorResponse;
        } catch (\Exception $exception) {
            return $errorResponse;
        }
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function actionAnswerTestCheck()
    {
        $errorResponse = ['status' => 'error', 'message' => 'Щось пішло не так..'];

        if (!\Yii::$app->mutex->acquire('multiple-answer-check')) {
            \Yii::info('Пользователь попытался выполнить несколько раз подряд вход');

            return $errorResponse;
        }

        try {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            if (!\Yii::$app->request->isPost || \Yii::$app->siteUser->isGuest) {
                throw new BadRequestHttpException();
            }

            $request = \Yii::$app->request;
            $token = $request->post(\Yii::$app->request->csrfParam, '');

            if (!\Yii::$app->request->validateCsrfToken($token)) {
                throw new BadRequestHttpException();
            }

            $teamId = \Yii::$app->siteUser->identity->team->id;
            $questionId = \Yii::$app->request->post('questionId');
            $answerId = (int)\Yii::$app->request->post('answerId');
            $groupId = \Yii::$app->request->post('groupId');

            if (!is_int($answerId)) {
                return [
                    'status' => 'error',
                    'message' => 'Ти звичайно крутий програміст, але відповідь має бути одна',
                ];
            }

            /** @var TeamAnswer $teamAnswer */
            $teamAnswer = TeamAnswer::find()
                ->where([
                    'team_id' => $teamId,
                    'question_id' => $questionId,
                    'answer_id' => null
                ])
                ->one();

            $currentTime = time();
            $timeEnded = false;

            if ($teamAnswer) {
                if ($currentTime - strtotime($teamAnswer->started_at) <= Question::TIME_FOR_ANSWER) {
                    $teamAnswer->answer_id = $answerId;
                    $teamAnswer->answered_at = date('Y-m-d H:i:s');
                } else {
                    $teamAnswer->answer_id = -1;
                    $timeEnded = true;
                }

                if ($teamAnswer->update()) {
                    $isAnswerCorrect = false;
                    $answerCorrectId = -1;

                    if (!$isAnswerCorrect) {
                        /** @var Answer $teamAnswerCorrect */
                        $teamAnswerCorrect = Answer::find()
                            ->where([
                                'question_id' => $questionId,
                                'is_correct' => 1,
                            ])
                            ->one();

                        $answerCorrectId = $teamAnswerCorrect->id;
                    }

                    /** @var Question $blockQuestion */
                    $blockQuestion = Question::findNextQuestion($groupId);

                    if ($blockQuestion) {
                        if (!$timeEnded) {
                            return [
                                'status' => 'success',
                                'message' => 'Відповідь зараховано! Наступне питання вже перед тобою',
                                'isCorrect' => $isAnswerCorrect,
                                'answerCorrectId' => $answerCorrectId,
                                'newQuestion' => $this->renderAjax('/tasks/question-body',
                                    ['question' => $blockQuestion]),
                            ];
                        }

                        /** @var TeamAnswer[] $otherBlockAnswers */
                        $otherBlockAnswers = TeamAnswer::find()->where([
                            'question_id' => $blockQuestion->id,
                            'team_id' => \Yii::$app->siteUser->identity->team->id,
                        ])
                            ->all();

                        foreach ($otherBlockAnswers as $failedAnswer) {
                            $failedAnswer->answer_id = -1;
                            $failedAnswer->update();
                        }
                    }

                    if ($timeEnded) {
                        /** @var TeamAnswer[] $teamAnswer */
                        $teamAnswer = TeamAnswer::find()
                            ->where([
                                'team_id' => $teamId,
                                'question_id' => $questionId,
                                'answer_id' => null
                            ])
                            ->all();

                        foreach ($teamAnswer as $answer) {
                            $answer->answer_id = -1;
                            $answer->update();
                        }

                        return [
                            'status' => 'error',
                            'message' => 'Нажаль ти не встиг за відведений час..',
                            'blockFinishedUrl' => '/tasks',
                        ];
                    }

                    return [
                        'status' => 'success',
                        'isCorrect' => $isAnswerCorrect,
                        'answerCorrectId' => $answerCorrectId,
                        'message' => 'Відповіді зараховано вчасно!',
                        'blockFinishedUrl' => '/tasks/test-finished/' . Test::findOne($groupId)->task->hash,
                    ];
                }

                return ['status' => 'error', 'message' => 'Відповідь не опрацьовано'];
            }

            return ['status' => 'error', 'message' => 'Питання не знайдено'];
        } catch (BadRequestHttpException $exception) {
            return $errorResponse;
        } catch (\Exception $exception) {
            return $errorResponse;
        }
    }

    /**
     * @return array
     * @throws BadRequestHttpException
     * @throws \yii\db\Exception
     */
    public function actionMark()
    {
        if (!\Yii::$app->request->isPost || \Yii::$app->siteUser->isGuest) {
            throw new BadRequestHttpException();
        }

        ignore_user_abort(true);

        $ids = (array)ArrayHelper::getValue(\Yii::$app->request->post(), 'ids', []);
        $status = ArrayHelper::getValue(\Yii::$app->request->post(), 'status');
        $markAll = ArrayHelper::getValue(\Yii::$app->request->post(), 'mark_all');

        $notificationStatuses = DefNotificationUser::getListStatuses('keys');

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ((!$ids || !in_array($status, $notificationStatuses, false)) && !$markAll) {
            return ['status' => 'error'];
        }

        if ($markAll) {
            Task::readAll();
        } else {
            Task::readByIds($ids);
        }

        return ['status' => 'ok'];
    }

    /**
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionCounter()
    {
        if (\Yii::$app->siteUser->isGuest) {
            throw new BadRequestHttpException();
        }

        $counters = Task::getUserTasksCounters();

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'counters' => $counters,
        ];
    }

    /**
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionRead($id)
    {
        $model = $this->findModel($id);

        if (!$model) {
            throw new NotFoundHttpException(AppMsg::t('Страница не найдена.'));
        }

        if ($model->setRead()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function actionReadall()
    {
        return Task::readAll();
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionLoadMore()
    {
        if (!\Yii::$app->request->isPost || !\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;

        $lastTaskId = (int)\Yii::$app->request->post('lastId');

        if (!$lastTaskId) {
            return [];
        }

        $tag = \Yii::$app->request->get('tag');

        $queryParams = [
            'limit' => Task::ITEMS_PER_PAGE + 1,
            'where' => ['<', 'task_id', $lastTaskId],
            'tags' => $tag,
        ];

        //$olderTasks = \yii\easyii\modules\tasks\api\Task::items($queryParams);
        $olderTasks = [];
        $hasToLoadMore = false;
        $lastItemId = 0;

        if (count($olderTasks) > Task::ITEMS_PER_PAGE) {
            $hasToLoadMore = true;
            $lastItemId = $olderTasks[count($olderTasks) - 1]->id;

            array_pop($olderTasks);
        }

        $tasks = '';

        foreach ($olderTasks as $task) {
            $tasks .= $this->renderPartial('/tasks/task-item',
                ['task' => $task]);
        }

        return [
            'hasToLoadMore' => $hasToLoadMore,
            'lastItemId' => $lastItemId,
            'tasks' => $tasks,
        ];
    }
}