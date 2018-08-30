<?php

namespace yii\easyii\modules\siteusers\controllers;

use app\models\SiteUser;
use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\behaviors\StatusController;
use yii\easyii\components\Controller;

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

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => SiteUser::find()->orderBy('created_at')
        ]);

        return $this->render('index', [
            'data' => $data,
        ]);
    }

    public function actionOn($id)
    {
        return $this->changeStatus($id, SiteUser::STATUS_ACTIVE);
    }

    public function actionOff($id)
    {
        return $this->changeStatus($id, SiteUser::STATUS_DISABLED);
    }
}