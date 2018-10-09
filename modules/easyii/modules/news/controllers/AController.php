<?php

namespace yii\easyii\modules\news\controllers;

use app\models\SiteUser;
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
                'model' => News::className()
            ]
        ];
    }

    public function actionIndex()
    {
        $dataNews = new ActiveDataProvider([
            'query' => News::find()->where(['category' => CategoryHelper::CATEGORY_NEWS])->orderBy('time')
        ]);

        return $this->render('index', [
            'dataNews' => $dataNews
        ]);
    }

    /**
     * @return array|string|\yii\web\Response
     * @throws \yii\web\HttpException
     */
    public function actionCreate()
    {
        $model = new News;
        $model->time = time();

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
                        ->where(['status' => SiteUser::STATUS_ACTIVE])->all(), 'id');

                    foreach ($siteUsers as $userId) {
                        $newsUser = new NewsUser();
                        $newsUser->site_user_id = $userId;
                        $newsUser->news_id = $model->news_id;

                        if(!$newsUser->save()) {
                            $this->flash('error', Yii::t('easyii/news',
                                'Notifications not sent :' . VarDumper::export($newsUser->getErrors())));
                        }
                    }

                    $this->flash('success', Yii::t('easyii/news', 'News created'));
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
        $model = News::find()->where(['news_id' => $id])->multilingual()->one();

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
                    $this->flash('success', Yii::t('easyii/news', 'News updated'));
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
     * @return string|\yii\web\Response
     */
    public function actionPhotos($id)
    {
        if (!($model = News::findOne($id))) {
            return $this->redirect(['/admin/' . $this->module->id]);
        }

        return $this->render('photos', [
            'model' => $model,
        ]);
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
        if (($model = News::findOne($id))) {
            $model->delete();
        } else {
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii/news', 'News deleted'));
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
        $model = News::findOne($id);

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
        return $this->changeStatus($id, News::STATUS_ON);
    }

    public function actionOff($id)
    {
        return $this->changeStatus($id, News::STATUS_OFF);
    }
}