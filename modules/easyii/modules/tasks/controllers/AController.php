<?php

namespace yii\easyii\modules\tasks\controllers;

use app\models\definitions\DefTask;
use app\models\search\TaskSearch;
use app\models\Task;
use app\models\WrittenTaskAnswer;
use Yii;
use yii\easyii\behaviors\StatusController;
use yii\easyii\components\Controller;
use yii\easyii\modules\tasks\models\AddTaskForm;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class AController
 * @package yii\easyii\modules\tasks\controllers
 */
class AController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => StatusController::className(),
                'model' => Task::className(),
            ]
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TaskSearch();
        $queryParams = \Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
            'data' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     */
    public function actionView($id)
    {
        $model = WrittenTaskAnswer::findOne($id);

        if ($model === null) {
            $this->flash('error', Yii::t('easyii', 'Not found'));
            return $this->redirect(['/admin/' . $this->module->id . '/']);
        }

        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * @return array|string|Response
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $model = new AddTaskForm();

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($model->add()) {
                $this->flash('success', Yii::t('easyii', 'Завдання створено'));
                return $this->redirect(['/admin/' . $this->module->id]);
            }

            $this->flash('error', Yii::t('easyii', 'Помилка створення завдання'));
            return $this->refresh();
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * @param int $id
     * @return array|string|Response
     * @throws \yii\db\Exception
     */
    public function actionEdit($id)
    {
        $task = Task::findOne([$id]);

        $model = new AddTaskForm();
        $model->setTask($task);

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
                $this->flash('success', Yii::t('easyii', 'Завдання оновлено'));
            } else {
                $this->flash('error', Yii::t('easyii', 'Помилка при оновленні завдання'));
            }

            return $this->refresh();
        }

        return $this->render('edit', [
            'model' => $model
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionClearImage($id)
    {
        $model = Task::findOne($id);

        if ($model === null) {
            $this->flash('error', Yii::t('easyii', 'Not found'));
        } else {
            $model->image = '';
            if ($model->update()) {
                @unlink(Yii::getAlias('@webroot') . $model->image);
                $this->flash('success', Yii::t('easyii', 'Image cleared'));
            } else {
                $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
            }
        }
        return $this->back();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function actionOn($id)
    {
        return $this->changeStatus($id, DefTask::STATUS_ON);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \Throwable
     */
    public function actionOff($id)
    {
        return $this->changeStatus($id, DefTask::STATUS_OFF);
    }
}