<?php

namespace yii\easyii\modules\teams\controllers;

use app\models\definitions\DefTeam;
use app\models\search\SiteUserSearch;
use app\models\search\TeamSearch;
use app\models\SiteUser;
use app\models\Team;
use Yii;
use yii\easyii\behaviors\StatusController;
use yii\easyii\components\Controller;
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

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($model->update()) {
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

    public function actionOn($id)
    {
        return $this->changeStatus($id, DefTeam::STATUS_ACTIVE);
    }

    public function actionOff($id)
    {
        return $this->changeStatus($id, DefTeam::STATUS_DISABLED);
    }
}