<?php

namespace yii\easyii\modules\notification\controllers;

use app\models\definitions\DefNotification;
use app\models\NotificationUser;
use app\models\search\NotificationUserSearch;
use Yii;
use yii\easyii\components\Controller;

/**
 * Class AController
 * @package yii\easyii\modules\notification\controllers
 */
class AController extends Controller
{
    /**
     * @param string $category
     * @param string $status
     *
     * @return string
     */
    public function actionIndex($category = '', $status = '')
    {
        $listCategories = DefNotification::getListCategories();

        if (empty($category) || !array_key_exists($category, $listCategories)) {
            $category = 'all';
        }

        $searchModel = new NotificationUserSearch();

        $userId = Yii::$app->request->get('userId');

        $searchModel->userId = $userId;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $userCategories = NotificationUser::getUserCategories($userId);

        $data = [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'category' => $category,
            'status' => $status,
            'userCategories' => $userCategories,
            'listCategories' => $listCategories,
        ];

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('index', $data);
        }

        return $this->render('index', $data);
    }


}