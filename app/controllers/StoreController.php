<?php

namespace app\controllers;

use app\components\Controller;

/**
 * Class StoreController
 * @package app\controllers
 */
class StoreController extends Controller
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

        \Yii::$app->seo->setTitle('Магазин');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        return $this->render('index',
            [

            ]
        );
    }
}
