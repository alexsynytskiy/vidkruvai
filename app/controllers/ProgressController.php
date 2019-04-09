<?php

namespace app\controllers;

use app\components\Controller;
use app\models\Category;

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

        $saleData = [1,2,3,4,5];

        return $this->render('index',
            [
                'data' => $data,
                'saleData' => $saleData,
            ]
        );
    }
}
