<?php

namespace yii\easyii\modules\school\controllers;

use app\models\City;
use app\models\School;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\easyii\components\Controller;
use yii\easyii\modules\school\models\AddSchoolForm;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class AController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'get-state-cities' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
//        $searchModel = new SchoolSearch();
//        $queryParams = \Yii::$app->request->queryParams;
//        $dataProvider = $searchModel->search($queryParams);
//
//        return $this->render('index', [
//            'data' => $dataProvider,
//            'searchModel' => $searchModel,
//        ]);

        $query = School::find()->alias('s')->select('s.*')->addSelect(
            new Expression('(SELECT COUNT(su.id) count_users FROM `school` `sc` 
            INNER JOIN `site_user` `su` ON su.school_id = sc.id 
            WHERE `sc`.`id`= s.id GROUP BY `su`.`school_id`) as usersCount')
        )
            ->orderBy('usersCount DESC');

        $data = new ActiveDataProvider([
            'query' => $query
        ]);

        return $this->render('index', [
            'data' => $data,
        ]);
    }

    /**
     * @return array|string|\yii\web\Response
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $model = new AddSchoolForm();

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($model->add()) {
                $this->flash('success', Yii::t('easyii', 'Школа создана'));
                return $this->redirect(['/admin/' . $this->module->id]);
            }

            $this->flash('error', Yii::t('easyii', 'Ошибка создания школы'));
            return $this->refresh();
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * @param $id
     * @return array|string|Response
     * @throws \yii\db\Exception
     */
    public function actionEdit($id)
    {
        $school = School::findOne([$id]);

        $model = new AddSchoolForm();
        $model->setSchool($school);

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
                $this->flash('success', Yii::t('easyii', 'School updated'));
            } else {
                $this->flash('error', Yii::t('easyii', 'Update error.'));
            }
            return $this->refresh();
        }

        return $this->render('edit', [
            'model' => $model,
            'cities' => City::getList($model->state_id),
        ]);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function actionGetStateCities()
    {
        $errorResponse = ['status' => 'error', 'message' => 'Щось пішло не так..'];

        try {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $request = \Yii::$app->request;
            $token = $request->post(\Yii::$app->request->csrfParam);

            if (!\Yii::$app->request->validateCsrfToken($token)) {
                throw new BadRequestHttpException();
            }

            $stateId = \Yii::$app->request->post('stateId');
            $cities = City::getList($stateId);

            if ($cities) {
                return ['status' => 'success', 'cities' => $cities];
            }

            return ['status' => 'error', 'message' => 'Користувача не знайдено'];
        } catch (BadRequestHttpException $exception) {
            return $errorResponse;
        } catch (\Exception $exception) {
            return $errorResponse;
        }
    }
}
