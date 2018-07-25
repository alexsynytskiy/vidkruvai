<?php

namespace yii\easyii\modules\feedback\controllers;

use Yii;
use yii\easyii\modules\feedback\models\Feedback as FeedbackModel;

/**
 * Class SendController
 * @package yii\easyii\modules\feedback\controllers
 */
class SendController extends \yii\web\Controller
{
    /**
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        $model = new FeedbackModel;

        $request = Yii::$app->request;

        if ($model->load($request->post())) {

            $returnUrl = $request->post('successUrl');

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'Дякуємо за відгук! Найближчим часом ми відповімо');
            } else {
                $returnUrl = $request->post('errorUrl');
                \Yii::$app->session->setFlash('danger', 'Щось пішло не так.. Спробуйте пізніше, або зателефонуйте');
            }

            return $this->redirect($returnUrl);
        }

        return $this->redirect(Yii::$app->request->baseUrl);
    }
}