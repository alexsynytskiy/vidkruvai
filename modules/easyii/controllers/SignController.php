<?php
namespace yii\easyii\controllers;

use Yii;
use yii\easyii\models;

class SignController extends \yii\web\Controller
{
    public $layout = 'empty';
    public $enableCsrfValidation = false;

    public function actionIn()
    {
        if(!\Yii::$app->siteUser->isGuest) {
            \Yii::$app->siteUser->logout();
        }

        $model = new models\LoginForm;

        if (!Yii::$app->user->isGuest || ($model->load(Yii::$app->request->post()) && $model->login())) {
            return $this->redirect(Yii::$app->user->getReturnUrl(['/admin']));
        }

        return $this->render('in', [
            'model' => $model,
        ]);
    }

    public function actionOut()
    {
        Yii::$app->user->logout();

        return $this->redirect(Yii::$app->homeUrl);
    }
}