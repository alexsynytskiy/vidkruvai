<?php

namespace yii\easyii\modules\teams\controllers;

use app\models\definitions\DefEntityAchievement;
use app\models\definitions\DefNotification;
use app\models\definitions\DefTeam;
use app\models\definitions\DefTeamSiteUser;
use app\models\EntityAchievement;
use app\models\EntityAward;
use app\models\EntityLevelHistory;
use app\models\Notification;
use app\models\NotificationUser;
use app\models\search\TeamSearch;
use app\models\SiteUser;
use app\models\Team;
use app\models\TeamAnswer;
use app\models\TeamSiteUser;
use app\models\WrittenTaskAnswer;
use Yii;
use yii\easyii\behaviors\StatusController;
use yii\easyii\components\Controller;
use app\models\TasksUser;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class AController
 * @package yii\easyii\modules\teams\controllers
 */
class AController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => StatusController::className(),
                'model' => Team::className(),
            ]
        ];
    }

    public function actionIndex()
    {
        $searchModel = new TeamSearch();
        $queryParams = \Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
            'data' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * @param $id
     * @return array|string|Response
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionEdit($id)
    {
        $model = Team::findOne([$id]);

        if ($model === null) {
            $this->flash('error', Yii::t('easyii', 'Not found'));
            return $this->redirect(['/admin/' . $this->module->id]);
        }

        $teamOldStatus = $model->status;

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($model->update()) {
                if ($teamOldStatus !== $model->status) {
                    if ($model->status === DefTeam::STATUS_ACTIVE) {
                        $model->notifyTeamAboutTasks();
                    }

                    if ($model->status === DefTeam::STATUS_UNCONFIRMED) {
                        $model->removeNotifiesTeamAboutTasks();
                    }
                }

                $this->flash('success', Yii::t('easyii', 'Команда обновлена'));
            } else {
                $this->flash('error', Yii::t('easyii', 'Ошибка обновления команды'));
            }
            return $this->refresh();
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function actionRemoveTeam()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $errorResponse = ['status' => 'error', 'message' => 'Щось пішло не так..'];

        if (!\Yii::$app->mutex->acquire('multiple-delete-team')) {
            \Yii::info('Пользователь попытался несколько раз удалить команду');

            return $errorResponse;
        }

        try {
            if (!\Yii::$app->request->isPost || \Yii::$app->user->isGuest) {
                throw new BadRequestHttpException();
            }

            $request = \Yii::$app->request;
            $token = $request->post(\Yii::$app->request->csrfParam, '');

            if (!\Yii::$app->request->validateCsrfToken($token)) {
                throw new BadRequestHttpException();
            }

            $teamId = $request->post('teamId', '');

            $team = Team::findOne([$teamId]);

            if ($team) {
                $writtenTasksAnswers = WrittenTaskAnswer::findAll(['team_id' => $teamId]);
                $this->removeObjects($writtenTasksAnswers);
                $testsTasksAnswers = TeamAnswer::findAll(['team_id' => $teamId]);
                $this->removeObjects($testsTasksAnswers);

                foreach ($team->teamUsers as $teamUser) {
                    $notificationsTasks = TasksUser::findAll(['site_user_id' => $teamUser->site_user_id]);
                    $this->removeObjects($notificationsTasks);

                    $userTeamNotifications = Notification::find()->alias('n')
                        ->innerJoin(NotificationUser::tableName() . ' nu', 'n.id = nu.n_id')
                        ->where(['nu.user_id' => $teamUser->site_user_id])
                        ->andWhere(['n.category' => DefNotification::CATEGORY_TEAM])->all();
                    $this->removeObjects($userTeamNotifications);

                    $achievements = EntityAchievement::findAll(['entity_id' => $teamId, 'entity_type' => DefEntityAchievement::ENTITY_TEAM]);
                    $this->removeObjects($achievements);
                    $awards = EntityAward::findAll(['entity_id' => $teamId, 'entity_type' => DefEntityAchievement::ENTITY_TEAM]);
                    $this->removeObjects($awards);
                    $levels = EntityLevelHistory::findAll(['entity_id' => $teamId, 'entity_type' => DefEntityAchievement::ENTITY_TEAM]);
                    $this->removeObjects($levels);

                    $teamUser->delete();
                }
            }

            $team->delete();

            return ['status' => 'success', 'message' => 'Команду та всі дані видалено'];
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
    public function actionSendInvitationAgain()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $errorResponse = ['status' => 'error', 'message' => 'Щось пішло не так..'];

        if (!\Yii::$app->mutex->acquire('multiple-delete-team')) {
            \Yii::info('Пользователь попытался несколько раз отправить повторное приглашение');

            return $errorResponse;
        }

        try {
            if (!\Yii::$app->request->isPost || \Yii::$app->user->isGuest) {
                throw new BadRequestHttpException();
            }

            $request = \Yii::$app->request;
            $token = $request->post(\Yii::$app->request->csrfParam, '');

            if (!\Yii::$app->request->validateCsrfToken($token)) {
                throw new BadRequestHttpException();
            }

            $hash = $request->post('hash', '');

            $memberInvitation = TeamSiteUser::findOne(['hash' => $hash]);

            if($memberInvitation && $memberInvitation->role !== DefTeamSiteUser::ROLE_CAPTAIN) {
                $memberInvitation->mailInvitedUser();
            }

            return ['status' => 'success', 'message' => 'Повторне запрошення відправлено'];
        } catch (BadRequestHttpException $exception) {
            return $errorResponse;
        } catch (\Exception $exception) {
            return $errorResponse;
        }
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function actionOn($id)
    {
        $team = Team::findOne([$id]);

        if ($team) {
            $team->notifyTeamAboutTasks();
        }

        return $this->changeStatus($id, DefTeam::STATUS_ACTIVE);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \Throwable
     */
    public function actionOff($id)
    {
        $team = Team::findOne([$id]);

        if ($team) {
            $team->removeNotifiesTeamAboutTasks();
        }

        return $this->changeStatus($id, DefTeam::STATUS_UNCONFIRMED);
    }
}