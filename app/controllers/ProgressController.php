<?php

namespace app\controllers;

use app\components\AppMsg;
use app\components\Controller;
use app\models\Category;
use app\models\Sale;
use app\models\WrittenTaskAnswer;
use yii\db\Query;

/**
 * Class ProgressController
 * @package app\controllers
 */
class ProgressController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        \Yii::$app->seo->setTitle('Прогрес');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        $categories = Category::find()->storeCategory()->all();
        $data = [];

        /** @var Category $category */
        foreach ($categories as $category) {
            $data[$category->name] = $category->childrenSubItemsBoughtCount();
        }

        $teamId = \Yii::$app->siteUser->identity->team->id;

        $saleData = Sale::find()->where(['team_id' => $teamId])->all();

        $executedTasksData = (new Query)->from(WrittenTaskAnswer::tableName())->select(['MONTHNAME(updated_at) as month', 'count(id) value'])
            ->where(['team_id' => $teamId])->andWhere(['!=', 'text', ['', null]])->groupBy(['MONTH(updated_at)'])->all();

        foreach ($executedTasksData as $monthData) {
            $monthData['month'] = \Yii::t('app', $monthData['month']);
        }

        return $this->render('index',
            [
                'data' => $data,
                'saleData' => $saleData,
                'executedTasksData' => $executedTasksData,
            ]
        );
    }
}
