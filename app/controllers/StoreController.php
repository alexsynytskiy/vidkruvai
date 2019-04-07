<?php

namespace app\controllers;

use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionModalPrepare()
    {
        if (!\Yii::$app->request->isPost || !\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;

        $itemId = (int)\Yii::$app->request->post('itemId');

        if (!$itemId) {
            return [];
        }

        $item = [
            'level' => 1,
            'levelsCount' => 5,
            'categoryName' => 'Інфраструктура',
            'itemName' => 'Вхідна група',
            'itemShort' => 'Короткий опис у кілька рядків',
            'cost' => 400,
            'icon' => '/img/floor1.svg'
        ];

        return [
            'modalContent' => $this->renderPartial('/store/modal-content', ['item' => $item]),
        ];
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionBuy()
    {
        if (!\Yii::$app->request->isPost || !\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;

        $itemId = (int)\Yii::$app->request->post('itemId');

        if (!$itemId) {
            return [];
        }



        return [
            'status' => 'ok',
        ];
    }
}
