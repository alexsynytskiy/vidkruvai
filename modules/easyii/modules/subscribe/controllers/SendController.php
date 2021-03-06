<?php
namespace yii\easyii\modules\subscribe\controllers;

use Yii;
use yii\easyii\modules\subscribe\api\Subscribe;
use yii\helpers\VarDumper;
use yii\widgets\ActiveForm;

use yii\easyii\modules\subscribe\models\Subscriber;

class SendController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new Subscriber;
        $request = Yii::$app->request;

        if ($model->load($request->post())) {
            if($request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                $returnUrl = $model->save() ? $request->post('successUrl') : $request->post('errorUrl');
                \Yii::$app->session->setFlash('success', 'Дякуємо за підписку!');
                return $this->redirect($returnUrl);
            }
        }
        else {
            return $this->redirect(Yii::$app->request->baseUrl);
        }
    }

    public function actionUnsubscribe($email)
    {
        if($email && filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            Subscriber::deleteAll(['email' => $email]);
            \Yii::$app->session->setFlash('success', 'Ви успішно відписані');
        }
        else{
            throw new \yii\web\BadRequestHttpException(Yii::t('easyii/subscribe/api', 'Incorrect E-mail'));
        }
    }
}