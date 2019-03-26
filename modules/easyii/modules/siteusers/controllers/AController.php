<?php

namespace yii\easyii\modules\siteusers\controllers;

use app\models\definitions\DefSiteUser;
use app\models\search\SiteUserSearch;
use app\models\SiteUser;
use Yii;
use yii\easyii\behaviors\StatusController;
use yii\easyii\components\Controller;
use yii\web\BadRequestHttpException;
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
        $searchModel = new SiteUserSearch();
        $queryParams = \Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($queryParams);

        $showStates = true;

        if ($queryParams) {
            $showStates = false;
        }

        return $this->render('index', [
            'data' => $dataProvider,
            'searchModel' => $searchModel,
            'showStates' => $showStates,
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
        return $this->changeStatus($id, DefSiteUser::STATUS_ACTIVE);
    }

    public function actionOff($id)
    {
        return $this->changeStatus($id, DefSiteUser::STATUS_BLOCKED);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function actionDropUserPassword()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $errorResponse = ['status' => 'error', 'message' => 'Щось пішло не так..'];

        if (!\Yii::$app->mutex->acquire('multiple-set-default-password')) {
            \Yii::info('Пользователь попытался несколько раз изменить пароль на 111111');

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

            $userId = $request->post('userId', '');

            $user = SiteUser::findOne([$userId]);

            if($user) {
                $user->auth_key = 'EDmbQNPAKECHdP9gSX8vSUTAD84q-d4t';
                $user->password = '$2y$13$OgQIiXAUZDxBFkhgK90Cn.H1yYTV5qu.7Nh.uDVlzgDH/eqCCwNu6';

                if($user->update()) {
                    return ['status' => 'success', 'message' => 'Пароль змінено на 111111'];
                }
            }

            return $errorResponse;
        } catch (BadRequestHttpException $exception) {
            return $errorResponse;
        } catch (\Exception $exception) {
            return $errorResponse;
        }
    }
}