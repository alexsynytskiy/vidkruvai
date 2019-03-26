<?php

namespace yii\easyii\modules\writtentasksanswers\controllers;

use app\models\search\WrittenTaskAnswerSearch;
use app\models\WrittenTaskAnswer;
use Yii;
use yii\easyii\components\Controller;

/**
 * Class AController
 * @package yii\easyii\modules\writtentasksanswers\controllers
 */
class AController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new WrittenTaskAnswerSearch();
        $queryParams = \Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($queryParams);

        return $this->render('index', [
            'data' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

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
}