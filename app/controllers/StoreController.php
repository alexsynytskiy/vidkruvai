<?php

namespace app\controllers;

use app\components\AppMsg;
use app\components\Controller;
use app\models\Category;
use app\models\Sale;
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
                'itemId' => $storeItem->id,
                'level' => $storeItem->category->slug,
                'levelsCount' => $parentCategory->children()->orderBy('id ASC')->count(),
                'categoryName' => $category->name,
                'itemName' => $storeItem->name,
                'itemShort' => $storeItem->description,
                'cost' => $storeItem->cost,
                'icon' => $storeItem->icon,
                'isBought' => $storeItem->isBought()
            ];
        }

        return [
            'modalContent' => $this->renderPartial('/store/modal-content', ['item' => $item]),
        ];
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
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

        $errors = [];
        $user = \Yii::$app->siteUser->identity;
        $saleItem = StoreItem::findOne($itemId);

        $transaction = \Yii::$app->db->beginTransaction();

        try {
            if ($saleItem) {
                $team = $user->team;
                $storeItemAlreadyBought = Sale::findOne(['store_item_id' => $itemId, 'team_id' => $team->id]);

                /** @var Category $category */
                $category = $saleItem->category->parents(0)->one();

                if ($storeItemAlreadyBought) {
                    return [
                        'status' => 'error',
                        'subStatus' => 'already-bought',
                        'message' => AppMsg::t('Такий елемент вже придбано!'),
                        'categorySlug' => $category ? $category->slug : '',
                        'categoryAllElements' => $category->childrenSubItemsCount(),
                        'categoryBoughtElements' => $category->childrenSubItemsBoughtCount(),
                    ];
                }

                if ($team->total_experience < $saleItem->cost) {
                    return [
                        'status' => 'error', 'subStatus' => 'less-experience', 'message' => AppMsg::t('Недостатньо досвіду! 
                        Виконайте доступні завдання. Якщо вже виконали - дочекайтесь нових завдань з нагородою щоб заробити більше балів'),
                    ];
                }

                if ($team && $user->isCaptain()) {
                    $team->total_experience -= $saleItem->cost;
                    if ($team->validate()) {
                        $team->update();

                        $sell = new Sale;
                        $sell->store_item_id = $itemId;
                        $sell->captain_id = $user->id;
                        $sell->team_id = $user->team->id;
                        $sell->team_balance = $team->total_experience;

                        if ($sell->validate()) {
                            $sell->save();
                        } else {
                            $errors[] = $sell->getErrors();
                        }
                    } else {
                        $errors[] = $team->getErrors();
                    }
                }
            }
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();

            $transaction->rollBack();
        }

        if (empty($errors)) {
            $transaction->commit();

            /** @var Category $category */
            $category = $saleItem->category->parents()->one();

            return [
                'status' => 'success',
                'message' => AppMsg::t('Елемент придбано!'),
                'categorySlug' => $category ? $category->slug : '',
                'categoryAllElements' => $category ? $category->childrenSubItemsCount() : 0,
                'categoryBoughtElements' => $category ? $category->childrenSubItemsBoughtCount() : 0,
            ];
        }

        return [
            'status' => 'error', 'message' => implode(', ', $errors),
        ];
    }
}
