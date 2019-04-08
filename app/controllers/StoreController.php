<?php

namespace app\controllers;

use app\components\Controller;
use app\models\Category;
use app\models\StoreItem;
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

        $categories = Category::find()->storeCategory()->all();

        return $this->render('index',
            [
                'categories' => $categories,
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
        $item = [];

        if (!$itemId) {
            return $item;
        }

        $storeItem = StoreItem::findOne($itemId);

        if ($storeItem) {
            $category = $storeItem->category;

            $parentCategory = $storeItem->category->parents()->one();

            $item = [
                'level' => $storeItem->category->slug,
                'levelsCount' => $parentCategory->children()->orderBy('id ASC')->count(),
                'categoryName' => $category->name,
                'itemName' => $storeItem->name,
                'itemShort' => $storeItem->description,
                'cost' => $storeItem->cost,
                'icon' => $storeItem->icon,
            ];
        }

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
