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
            $data[$category->name] = mt_rand(0, 10);
        }

        return $this->render('index',
            [
                'data' => $data,
            ]
        );
    }
}
