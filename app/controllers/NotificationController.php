<?php
namespace acp\controllers;

use app\components\LogHelper;
use acp\models\definitions\DefNotificationUser;
use acp\models\Notification;
use Yii;
use acp\models\definitions\DefNotification;
use acp\models\NotificationUser;
use acp\models\search\NotificationUserSearch;
use acp\components\Controller;
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
    public function actionIndex($category = '', $status = '') {
        $listCategories = DefNotification::getListCategories();

        if(empty($category) || !in_array($category, array_keys($listCategories))) {
            $category = 'all';
        }

        $searchModel = new NotificationUserSearch();

        if(Yii::$app->user->isInGroup('admin')) {
            $userId = Yii::$app->request->get('userId') ?: Yii::$app->user->id;
        } else {
            $userId = Yii::$app->user->id;
        }

        $searchModel->userId = $userId;
        $dataProvider        = $searchModel->search(Yii::$app->request->queryParams);
        $userCategories      = NotificationUser::getUserCategories($userId);

        $data = [
            'searchModel'    => $searchModel,
            'dataProvider'   => $dataProvider,
            'category'       => $category,
            'status'         => $status,
            'userCategories' => $userCategories,
            'listCategories' => $listCategories,
        ];

        if(Yii::$app->request->isAjax) {
            return $this->renderAjax('index', $data);
        }

        return $this->render('index', $data);
    }

    /**
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionMark() {
        if(!Yii::$app->request->isAjax) {
            throw new BadRequestHttpException('Bad request.');
        }

        ignore_user_abort(true);

        $ids     = (array)ArrayHelper::getValue(Yii::$app->request->post(), 'ids', []);
        $status  = ArrayHelper::getValue(Yii::$app->request->post(), 'status');
        $markAll = ArrayHelper::getValue(Yii::$app->request->post(), 'mark_all');
        $userId  = Yii::$app->user->id;

        $notificationStatuses = DefNotificationUser::getListStatuses('keys');

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if((!$ids || !in_array($status, $notificationStatuses)) && !$markAll) {
            Yii::warning("Пользователь пытался отметить уведомление. Но, либо не передал Notification IDs (" . print_r($ids, true) . "). Либо указал не существующий статус уведомления ({$status}).", LogHelper::CATEGORY_USER_HACK);

            return ['status' => 'error'];
        }

        try {
            $conditions = [
                'user_id' => $userId,
            ];
            if(!$markAll) {
                $conditions['n_id']   = $ids;
                $conditions['status'] = [DefNotificationUser::STATUS_NEW, DefNotificationUser::STATUS_READ];
            } else {
                $status = DefNotificationUser::STATUS_READ;

                $conditions['status'] = DefNotificationUser::STATUS_NEW;
            }

            NotificationUser::updateAll(['status' => $status], $conditions);
        }
        catch(\Exception $e) {
            Yii::error("Ошибка при попытке изменить статус уведомления. Notification IDs (" . print_r($ids, true) . "), статус ({$status}), ошибка: " . $e->getMessage(), LogHelper::CATEGORY_DB);

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
    public function actionMn($id) {
        $userId       = Yii::$app->user->id;
        $notification = (new Query)
            ->select(['n_id', 'target_link'])
            ->from(Notification::tableName())
            ->innerJoin(NotificationUser::tableName(), 'id = n_id AND user_id = :userId', [
                ':userId' => $userId,
            ])
            ->where(['n_id' => $id])
            ->limit(1)
            ->one();

        if($notification === false) {
            return $this->redirect(['/acp/dashboard']);
        }

        try {
            Yii::$app->db->createCommand('UPDATE ' . NotificationUser::tableName() . ' SET status = :status WHERE n_id = :nId AND user_id = :userId', [
                ':nId'    => $id,
                ':userId' => $userId,
                ':status' => DefNotificationUser::STATUS_READ,
            ])
                ->execute();
        }
        catch(\Exception $e) {
            Yii::error("Ошибка при попытке отметить прочитанным уведомление (ID {$id}). Error: " . $e->getMessage(), LogHelper::CATEGORY_DB);

            return $this->redirect(['/acp/dashboard']);
        }

        return $this->redirect($notification['target_link']);
    }

    /**
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionGetData() {
        if(!Yii::$app->request->isAjax) {
            throw new BadRequestHttpException('Bad request.');
        }

        //Disable debug-toolbar embedding into the view
        Yii::$app->getModule('debug')->allowedIPs = [];

        $counters = $this->getUserNotificationCounters();
        $content  = $this->renderAjax('_top-menu-notification-items');

        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'counters' => $counters,
            'content'  => $content,
        ];
    }
}