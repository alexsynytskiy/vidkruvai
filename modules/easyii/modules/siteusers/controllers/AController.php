<?php

namespace yii\easyii\modules\siteusers\controllers;

use app\models\SiteUser;
use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\behaviors\StatusController;
use yii\easyii\components\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

class AController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => StatusController::className(),
                'model' => SiteUser::className(),
            ]
        ];
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => SiteUser::find()->orderBy('created_at')
        ]);

        return $this->render('index', [
            'data' => $data,
        ]);
    }

    /**
     * @return array|string|Response
     */
    public function actionCreate()
    {
        $model = new SiteUser();

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($model->save()) {
                $this->flash('success', Yii::t('easyii', 'Пользователь создана'));
                return $this->redirect(['/admin/' . $this->module->id]);
            }

            $this->flash('error', Yii::t('easyii', 'Ошибка создания пользователя'));
            return $this->refresh();
        }

        return $this->render('create', [
            'model' => $model
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
        $model = SiteUser::findOne([$id]);

        if ($model === null) {
            $this->flash('error', Yii::t('easyii', 'Not found'));
            return $this->redirect(['/admin/' . $this->module->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($model->update()) {
                $this->flash('success', Yii::t('easyii', 'Пользователь обновлён'));
            } else {
                $this->flash('error', Yii::t('easyii', 'Ошибка обновления пользователя'));
            }
            return $this->refresh();
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionOn($id)
    {
        return $this->changeStatus($id, SiteUser::STATUS_ACTIVE);
    }

    public function actionOff($id)
    {
        return $this->changeStatus($id, SiteUser::STATUS_DISABLED);
    }
}