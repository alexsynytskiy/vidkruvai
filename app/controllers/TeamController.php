<?php

namespace app\controllers;

use app\components\Controller;
use app\models\definitions\DefNotification;
use app\models\forms\TeamCreateForm;
use yii\captcha\CaptchaAction;
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
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => CaptchaAction::className(),
                'height' => 50,
                'maxLength' => 4,
                'minLength' => 4,
            ],
        ];
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
                } else {
                    $model->avatar = $model->oldAttributes['avatar'];
                }
            }

            if ($model->createTeam()) {
                \Yii::$app->notification->addToUser(\Yii::$app->siteUser->identity,
                    DefNotification::CATEGORY_TEAM,
                    DefNotification::TYPE_TEAM_CREATED, null,
                    ['team_name' => $model->name, 'created_at' => date('d-M-Y H:i:s')]);

                $this->flash('success', \Yii::t('easyii', 'Team created'));
            } else {
                $this->flash('error', \Yii::t('easyii', 'Create error.'));
            }

            return $this->render('update-team', [
                'model' => $model
            ]);
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
                } else {
                    $model->avatar = $model->oldAttributes['avatar'];
                }
            }

            if ($model->updateTeam()) {
                $this->flash('success', \Yii::t('easyii', 'Team updated'));
            } else {
                $this->flash('error', \Yii::t('easyii', 'Create updated.'));
            }
        }

        return $this->render('update-team', [
            'model' => $model
        ]);
    }
}
