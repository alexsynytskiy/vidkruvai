<?php

namespace yii\easyii\modules\category\controllers;

use app\models\Category;
use app\models\search\CategorySearch;
use app\models\SiteUser;
use Yii;
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

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $queryParams = \Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
            'data' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * @return array|string|Response
     */
    public function actionCreate()
    {
        $model = new Category();

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
        $model = Category::findOne($id);

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
                $this->flash('success', Yii::t('easyii', 'Категория обновлена'));
            } else {
                $this->flash('error', Yii::t('easyii', 'Ошибка обновления категории'));
            }

            return $this->refresh();
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }
}