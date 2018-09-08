<?php

namespace app\controllers;

use app\components\Controller;
use app\models\definitions\DefNotification;
use app\models\definitions\DefNotificationUser;
use app\models\Notification;
use app\models\NotificationUser;
use app\models\search\NotificationUserSearch;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * Class NotificationController
 * @package acp\controllers
 */
class NotificationController extends Controller
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

        $searchModel->userId = Yii::$app->siteUser->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $userCategories = NotificationUser::getUserCategories(Yii::$app->siteUser->id);

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

    /**
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionMark()
    {
        if (!Yii::$app->request->isAjax) {
            throw new BadRequestHttpException('Bad request.');
        }

        ignore_user_abort(true);

        $ids = (array)ArrayHelper::getValue(Yii::$app->request->post(), 'ids', []);
        $status = ArrayHelper::getValue(Yii::$app->request->post(), 'status');
        $markAll = ArrayHelper::getValue(Yii::$app->request->post(), 'mark_all');
        $userId = Yii::$app->siteUser->id;

        $notificationStatuses = DefNotificationUser::getListStatuses('keys');

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ((!$ids || !in_array($status, $notificationStatuses, false)) && !$markAll) {
            return ['status' => 'error'];
        }

        try {
            $conditions = [
                'user_id' => $userId,
            ];
            if (!$markAll) {
                $conditions['n_id'] = $ids;
                $conditions['status'] = [DefNotificationUser::STATUS_NEW, DefNotificationUser::STATUS_READ];
            } else {
                $status = DefNotificationUser::STATUS_READ;

                $conditions['status'] = DefNotificationUser::STATUS_NEW;
            }

            NotificationUser::updateAll(['status' => $status], $conditions);
        } catch (\Exception $e) {
            return ['status' => 'error'];
        }

        return ['status' => 'ok'];
    }

    /**
     * Mark notification with target_link as read and redirect to the target page,
     *
     * @param $id
     *
     * @return \yii\web\Response
     */
    public function actionMn($id)
    {
        $userId = Yii::$app->siteUser->id;

        $notification = (new Query)
            ->select(['n_id', 'target_link'])
            ->from(Notification::tableName())
            ->innerJoin(NotificationUser::tableName(), 'id = n_id AND user_id = :userId', [
                ':userId' => $userId,
            ])
            ->where(['n_id' => $id])
            ->limit(1)
            ->one();

        if ($notification === false) {
            return $this->redirect(['/acp/dashboard']);
        }

        try {
            Yii::$app->db->createCommand('UPDATE ' . NotificationUser::tableName() .
                ' SET status = :status WHERE n_id = :nId AND user_id = :userId', [
                ':nId' => $id,
                ':userId' => $userId,
                ':status' => DefNotificationUser::STATUS_READ,
            ])
                ->execute();
        } catch (\Exception $e) {
            return $this->redirect(['/acp/dashboard']);
        }

        return $this->redirect($notification['target_link']);
    }

    /**
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionGetData()
    {
        if (!Yii::$app->request->isAjax) {
            throw new BadRequestHttpException('Bad request.');
        }

        $counters = $this->getUserNotificationCounters();
        $content = $this->renderAjax('_top-menu-notification-items');

        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'counters' => $counters,
            'content' => $content,
        ];
    }
}