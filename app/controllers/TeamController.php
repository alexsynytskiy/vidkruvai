<?php

namespace app\controllers;

use app\components\AppMsg;
use app\components\Controller;
use app\components\events\TeamRegisteredEvent;
use app\components\helpers\EntityHelper;
use app\components\TeamRegisteredEventHandler;
use app\models\Achievement;
use app\models\definitions\DefEntityAchievement;
use app\models\definitions\DefLevel;
use app\models\definitions\DefNotification;
use app\models\definitions\DefSiteUser;
use app\models\forms\TeamCreateForm;
use app\models\Level;
use app\models\Team;
use yii\easyii\helpers\Image;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/**
 * Class TeamController
 * @package app\controllers
 */
class TeamController extends Controller
{
    /**
     * Event name, uses for triggering event when user is registered
     */
    const EVENT_TEAM_REGISTERED = 'app.controllers.on-team-registered';

    public function init()
    {
        parent::init();

        \Yii::$app->on(self::EVENT_TEAM_REGISTERED, [new TeamRegisteredEventHandler(), 'handle']);
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
     * @param null $id
     * @return bool|string|Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionLevels($id = null)
    {
        parent::initMode(DefEntityAchievement::ENTITY_TEAM);

        \Yii::$app->seo->setTitle('Рівні команди');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        return parent::actionLevels($id);
    }

    /**
     * @param null $id
     * @return bool|string|Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionAchievements($id = null)
    {
        parent::initMode(DefEntityAchievement::ENTITY_TEAM);

        \Yii::$app->seo->setTitle('Досягнення команди');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        return parent::actionAchievements($id);
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        \Yii::$app->seo->setTitle('Команда');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        if (\Yii::$app->siteUser->identity->role === DefSiteUser::ROLE_MENTOR && !\Yii::$app->siteUser->identity->team) {
            return $this->render('mentor-no-team');
        }

        return $this->renderTeamProfilePage(\Yii::$app->siteUser->identity->team);
    }

    /**
     * @param Team $team
     * @param bool $isPreview
     *
     * @return string
     */
    public function renderTeamProfilePage($team, $isPreview = false)
    {
        $levelInfo = EntityHelper::getEntityLevelInfo(DefEntityAchievement::ENTITY_TEAM, $team->id);

        $previousLevels = [];
        $nextLevels = Level::getLevels($team->level_id, DefLevel::NEXT_LEVELS, DefEntityAchievement::ENTITY_TEAM);

        if (count($nextLevels) < 2) {
            $previousLevels = Level::getLevels($team->level_id, DefLevel::PREVIOUS_LEVELS, DefEntityAchievement::ENTITY_TEAM,
                2 - count($nextLevels));
        }

        $achievements = Achievement::getAchievementsInProgress($team->id, DefEntityAchievement::ENTITY_TEAM);

        if (count($achievements) < 3) {
            $achievementsToStart = Achievement::getAchievementsToStart($team->id, DefEntityAchievement::ENTITY_TEAM, 3 - count($achievements));
            $achievements = array_merge($achievements, $achievementsToStart);
        }

        if (count($achievements) < 3) {
            $achievementsFinished = Achievement::getAchievementsFinished($team->id, DefEntityAchievement::ENTITY_TEAM, 3 - count($achievements));
            $achievements = array_merge($achievementsFinished, $achievements);
        }

        return $this->render('index',
            [
                'showTeamInfo' => false,
                'preview' => $isPreview,
                'achievements' => $achievements,
                'levelInfo' => $levelInfo,
                'previousLevels' => $previousLevels,
                'nextLevels' => $nextLevels,
                'teamCredentials' => EntityHelper::getEntityCredentials(DefEntityAchievement::ENTITY_TEAM, $team->id)
            ]
        );
    }

    /**
     * @return array|bool|string|Response
     * @throws \yii\db\Exception
     * @throws \yii\web\HttpException
     */
    public function actionCreateTeam()
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        if (!\Yii::$app->mutex->acquire('multiple-team-creation')) {
            \Yii::info('Пользователь попытался несколько раз создать команду');

            throw new BadRequestHttpException();
        }

        if (\Yii::$app->siteUser->identity->team) {
            $this->flash('error', AppMsg::t('Ви вже створили команду! Тепер її можна лише редагувати'));
            $this->redirect('/team');
        }

        if (\Yii::$app->siteUser->identity->role === DefSiteUser::ROLE_MENTOR) {
            $this->flash('error', AppMsg::t('Створювати команди можуть лише власне учасники(не ментори). 
            Учасник, що створить команду автоматично стане її капітаном.'));
            $this->redirect('/team');
        }

        \Yii::$app->seo->setTitle('Створити команду');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        $model = new TeamCreateForm;
        $model->isNewRecord = true;

        if ($model && $model->load(\Yii::$app->request->post())) {
            if (\Yii::$app->request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if (isset($_FILES)) {
                $model->avatar = UploadedFile::getInstance($model, 'avatar');
                if ($model->avatar && $model->validate(['avatar'])) {
                    $model->avatar = Image::upload($model->avatar, 'team');
                }
            }

            $errors = [];

            /** @var bool|array $errors */
            $hasNoErrors = $model->createTeam($errors);

            if ($hasNoErrors === true) {
                $teamEvent = new TeamRegisteredEvent();
                $teamEvent->teamId = $model->getTeam()->id;
                \Yii::$app->trigger(self::EVENT_TEAM_REGISTERED, $teamEvent);

                \Yii::$app->notification->addToUser(\Yii::$app->siteUser->identity,
                    DefNotification::CATEGORY_TEAM,
                    DefNotification::TYPE_TEAM_CREATED, null,
                    ['team_name' => $model->name, 'created_at' => date('d-M-Y H:i:s')]);

                $this->flash('success', AppMsg::t('Команду створено'));

                return $this->redirect('/team');
            }

            $this->flash('danger', 'Виникли помилки при створенні команди. Якщо ви не розумієте їх, 
            або не згодні з обґрунтованістю - надішліть організаторам листа зі своїм ім\'ям та прізвищем (ім\'я та 
            прізвище виключно капітана команди), а також запишіть час!');

            $errors = $model->getErrorsSimple($errors);
            $this->flash('info', implode(', ', $errors));

            return $this->render('index');
        }

        return $this->render('create-team', [
            'model' => $model
        ]);
    }

    /**
     * @return array|bool|string|Response
     * @throws \yii\db\Exception
     * @throws \yii\web\HttpException
     */
    public function actionUpdateTeam()
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        if (!\Yii::$app->mutex->acquire('multiple-team-update')) {
            \Yii::info('Пользователь попытался несколько раз обновить команду');

            throw new BadRequestHttpException();
        }

        if (!\Yii::$app->siteUser->identity->isCaptain()) {
            $this->redirect('/team');
        }

        \Yii::$app->seo->setTitle('Редагувати команду');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        $model = new TeamCreateForm;
        $model->isNewRecord = false;

        $model->setTeam(\Yii::$app->siteUser->identity->team);

        if ($model && $model->load(\Yii::$app->request->post())) {
            if (\Yii::$app->request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if (isset($_FILES)) {
                $model->avatar = UploadedFile::getInstance($model, 'avatar');
                if ($model->avatar && $model->validate(['avatar'])) {
                    $model->avatar = Image::upload($model->avatar, 'team');
                }
            }

            $errors = [];

            /** @var bool|array $errors */
            $hasNoErrors = $model->updateTeam($errors);

            if ($hasNoErrors === true) {
                $this->flash('success', AppMsg::t('Команду оновлено!'));

                return $this->redirect('/team');
            }

            $this->flash('danger', 'Виникли помилки при оновленні команди. Якщо ви не розумієте їх, 
            або не згодні з обґрунтованістю - надішліть організаторам листа зі своїм ім\'ям та прізвищем(ім\'я та 
            прізвище виключно капітана команди), а також запишіть час!');

            $errors = $model->getErrorsSimple($errors);
            $this->flash('info', implode(', ', $errors));

            $model->setTeam(\Yii::$app->siteUser->identity->team);
        }

        return $this->render('update-team', [
            'model' => $model
        ]);
    }
}
