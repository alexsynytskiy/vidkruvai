<?php

namespace app\controllers;

use app\components\AppMsg;
use app\components\Controller;
use app\models\Category;
use app\models\Sale;
use app\models\StoreItem;
use app\models\Team;
use yii\helpers\ArrayHelper;
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

            $user = \Yii::$app->siteUser->identity;
            $team = $user->team;

            $item = [
                'itemId' => $storeItem->id,
                'level' => $storeItem->category->slug,
                'levelsCount' => $parentCategory->children()->orderBy('id ASC')->count(),
                'categoryName' => $category->name,
                'itemName' => $storeItem->name,
                'itemShort' => $storeItem->description,
                'cost' => $storeItem->teamAdoptedCost($team->id),
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
        $team = $user->team;
        $saleItem = StoreItem::findOne($itemId);

        $transaction = \Yii::$app->db->beginTransaction();

        try {
            if ($saleItem) {
                $storeItemAlreadyBought = Sale::findOne(['store_item_id' => $itemId, 'team_id' => $team->id]);

                /** @var Category $category */
                $category = $saleItem->category->parents()->one();

                if ($storeItemAlreadyBought) {
                    return ArrayHelper::merge([
                        'status' => 'error',
                        'subStatus' => 'already-bought',
                        'message' => AppMsg::t('Такий елемент вже придбано!'),
                        'categorySlug' => $category ? $category->slug : '',
                        'categoryAllElements' => $category->childrenSubItemsCount(),
                        'categoryBoughtElements' => $category->childrenSubItemsBoughtCount(),
                    ], $this->renderLevelElements($saleItem, $team));
                }

                if ($team->total_experience < $saleItem->teamAdoptedCost($team->id)) {
                    return [
                        'status' => 'error', 'subStatus' => 'less-experience', 'message' => AppMsg::t('Недостатньо досвіду! 
                        Виконайте доступні завдання. Якщо вже виконали - дочекайтесь нових завдань з нагородою щоб заробити більше балів'),
                    ];
                }

                if ($team && $user->isCaptain()) {
                    $cost = $saleItem->teamAdoptedCost($team->id);

                    $team->total_experience -= $cost;
                    $team->level_experience -= $cost;

                    if ($team->validate()) {
                        $team->update();

                        $sell = new Sale;
                        $sell->store_item_id = $itemId;
                        $sell->captain_id = $user->id;
                        $sell->team_id = $user->team->id;
                        $sell->city_id = $user->school->city_id;
                        $sell->team_balance = $team->total_experience;

                        if ($sell->validate()) {
                            $sell->save();

                            $sell->processCityElements($user);
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

            return ArrayHelper::merge([
                'status' => 'success',
                'message' => AppMsg::t('Елемент придбано!'),
                'categorySlug' => $category ? $category->slug : '',
                'categoryAllElements' => $category ? $category->childrenSubItemsCount() : 0,
                'categoryBoughtElements' => $category ? $category->childrenSubItemsBoughtCount() : 0,
            ], $this->renderLevelElements($saleItem, $team));
        }

        return [
            'status' => 'error', 'message' => implode(', ', $errors),
        ];
    }

    /**
     * @param StoreItem $saleItem
     * @param Team $team
     *
     * @return array
     */
    public function renderLevelElements($saleItem, $team)
    {
        $openNextLevel = $saleItem->category->levelPassed();

        $nextLevelElements = '';
        $currentLevelElements = '';

        if ($openNextLevel) {
            /** @var Category $nextLevel */
            $nextLevel = $saleItem->category->prev()->one();

            if ($nextLevel) {
                $this->renderLevel($nextLevel, $team, $nextLevelElements);
            }
        }

        $this->renderLevel($saleItem->category, $team, $currentLevelElements);

        return [
            'openNextLevel' => $openNextLevel,
            'nextLevelElements' => $nextLevelElements,
            'currentLevelElements' => $currentLevelElements,
        ];
    }

    /**
     * @param Category $level
     * @param Team $team
     * @param $levelElements
     */
    public function renderLevel($level, $team, &$levelElements)
    {
        foreach ($level->storeItems as $storeItem) {
            $itemBought = $storeItem->isBought();
            $itemLocked = $itemBought ? false : time() < strtotime($level->enabled_after) ||
                $team->total_experience < $storeItem->teamAdoptedCost($team->id);

            $levelElements .= $this->renderPartial('/store/store-item', [
                'storeItem' => $storeItem,
                'itemLocked' => $itemLocked,
                'itemBought' => $itemBought,
                'level' => $level,
                'teamId' => $team->id,
            ]);
        }
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionRulesRead()
    {
        if (!\Yii::$app->request->isPost || !\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;

        $errors = [];

        $teamId = \Yii::$app->siteUser->identity->team->id;
        $team = Team::findOne($teamId);

        if ($team) {
            $team->store_ready = 1;

            if ($team->update(false)) {
                return [
                    'status' => 'success',
                    'categories' => $this->renderPartial('/store/categories', [
                        'categories' => Category::find()->storeCategory()->all()
                    ])
                ];
            }

            $errors[] = $team->getErrors();
        }
        else {
            $errors[] = 'Команду не знайдено';
        }

        return [
            'status' => 'error', 'message' => implode(', ', $errors),
        ];
    }
}
