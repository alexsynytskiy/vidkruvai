<?php

namespace yii\easyii\modules\news\controllers;

use app\models\CommentChannel;
use app\models\definitions\DefSiteUser;
use app\models\definitions\DefTask;
use app\models\SiteUser;
use app\models\Task;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\easyii\behaviors\SortableDateController;
use yii\easyii\behaviors\StatusController;
use yii\easyii\components\Controller;
use yii\easyii\components\helpers\CategoryHelper;
use yii\easyii\helpers\Image;
use yii\easyii\modules\news\models\News;
use yii\easyii\modules\news\models\NewsUser;
use yii\easyii\modules\tasks\models\TasksUser;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class AController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => SortableDateController::className(),
                'model' => News::className(),
            ],
            [
                'class' => StatusController::className(),
                'model' => Task::className()
            ]
        ];
    }

    public function actionIndex()
    {
        $dataTasks = new ActiveDataProvider([
            'query' => Task::find()->orderBy('created_at DESC')
        ]);

        return $this->render('index', [
            'dataTasks' => $dataTasks
        ]);
    }

    /**
     * @return array|string|\yii\web\Response
     * @throws \yii\web\HttpException
     */
    public function actionCreate()
    {
        $model = new Task();

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                if (isset($_FILES) && $this->module->settings['enableThumb']) {
                    $model->image = UploadedFile::getInstance($model, 'image');
                    if ($model->image && $model->validate(['image'])) {
                        $model->image = Image::upload($model->image, 'news');
                    } else {
                        $model->image = '';
                    }
                }
                if ($model->save()) {
                    $siteUsers = ArrayHelper::getColumn((new Query)->select('id')
                        ->from(SiteUser::tableName())
                        ->where(['status' => DefSiteUser::STATUS_ACTIVE])->all(), 'id');

                    foreach ($siteUsers as $userId) {
                        $tasksUser = new TasksUser();
                        $tasksUser->site_user_id = $userId;
                        $tasksUser->task_id = $model->id;

                        if(!$tasksUser->save()) {
                            $this->flash('error', Yii::t('easyii/tasks',
                                'Notifications not sent :' . VarDumper::export($tasksUser->getErrors())));
                        }
                    }

                    $this->flash('success', Yii::t('easyii/tasks', 'Task created'));
                    return $this->redirect(['/admin/' . $this->module->id]);
                }

                $this->flash('error', Yii::t('easyii', 'Create error. {0}', $model->formatErrors()));
                return $this->refresh();
            }
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    /**
     * @param $id
     * @return array|string|\yii\web\Response
     * @throws \yii\web\HttpException
     */
    public function actionEdit($id)
    {
        $model = Task::findOne($id);

        if ($model === null) {
            $this->flash('error', Yii::t('easyii', 'Not found'));
            return $this->redirect(['/admin/' . $this->module->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                if (isset($_FILES) && $this->module->settings['enableThumb']) {
                    $model->image = UploadedFile::getInstance($model, 'image');
                    if ($model->image && $model->validate(['image'])) {
                        $model->image = Image::upload($model->image, 'news');
                    } else {
                        $model->image = $model->oldAttributes['image'];
                    }
                }

                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii/tasks', 'Task updated'));
                } else {
                    $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
                }
                return $this->refresh();
            }
        } else {
            return $this->render('edit', [
                'model' => $model
            ]);
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        if (($model = Task::findOne($id))) {
            $model->delete();
        } else {
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii/tasks', 'Task deleted'));
    }

    public function actionUp($id)
    {
        return $this->move($id, 'up');
    }

    public function actionDown($id)
    {
        return $this->move($id, 'down');
    }

    public function actionOn($id)
    {
        return $this->changeStatus($id, DefTask::STATUS_ON);
    }

    public function actionOff($id)
    {
        return $this->changeStatus($id, DefTask::STATUS_OFF);
    }
}