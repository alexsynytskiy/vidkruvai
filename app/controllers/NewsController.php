<?php

namespace app\controllers;

use app\components\AppMsg;
use app\components\Controller;
use app\components\helpers\LanguageHelper;
use app\models\definitions\DefNotificationUser;
use yii\easyii\modules\news\api\News;
use yii\easyii\modules\news\api\NewsObject;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class NewsController
 * @package app\controllers
 */
class NewsController extends Controller
{
    /**
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionMark()
    {
        if (!\Yii::$app->request->isPost || \Yii::$app->siteUser->isGuest) {
            throw new BadRequestHttpException();
        }

        ignore_user_abort(true);

        $ids = (array)ArrayHelper::getValue(\Yii::$app->request->post(), 'ids', []);
        $status = ArrayHelper::getValue(\Yii::$app->request->post(), 'status');
        $markAll = ArrayHelper::getValue(\Yii::$app->request->post(), 'mark_all');

        $notificationStatuses = DefNotificationUser::getListStatuses('keys');

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ((!$ids || !in_array($status, $notificationStatuses, false)) && !$markAll) {
            return ['status' => 'error'];
        }

        if ($markAll) {
            News::readAll();
        } else {
            News::readByIds($ids);
        }

        return ['status' => 'ok'];
    }

    /**
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionCounter()
    {
        if (\Yii::$app->siteUser->isGuest) {
            throw new BadRequestHttpException();
        }

        $counters = News::getUserNewsCounters();

        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'counters' => $counters,
        ];
    }

    /**
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionRead($id)
    {
        $model = $this->findModel($id);

        if (!$model) {
            throw new NotFoundHttpException(AppMsg::t('Страница не найдена.'));
        }

        if ($model->setRead()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function actionReadall()
    {
        return News::readAll();
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return NewsObject the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = \yii\easyii\modules\news\api\News::get(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionLoadMore()
    {
        if (!\Yii::$app->request->isPost || !\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;

        $lastNewsId = (int)\Yii::$app->request->post('lastId');

        if (!$lastNewsId) {
            return [];
        }

        $tag = \Yii::$app->request->get('tag');

        $queryParams = [
            'limit' => \yii\easyii\modules\news\models\News::ITEMS_PER_PAGE + 1,
            'where' => ['<', 'news_id', $lastNewsId],
            'tags' => $tag,
        ];

        if (\Yii::$app->language !== LanguageHelper::LANG_UK) {
            $queryParams = ArrayHelper::merge($queryParams, ['language' => LanguageHelper::LANG_EN]);
        }

        $olderNews = News::items($queryParams);
        $hasToLoadMore = false;
        $lastItemId = 0;

        if (count($olderNews) > \yii\easyii\modules\news\models\News::ITEMS_PER_PAGE) {
            $hasToLoadMore = true;
            $lastItemId = $olderNews[count($olderNews) - 1]->id;

            array_pop($olderNews);
        }

        $items = '';

        foreach ($olderNews as $item) {
            $items .= $this->renderPartial('/profile/news-item',
                ['item' => $item]);
        }

        return [
            'hasToLoadMore' => $hasToLoadMore,
            'lastItemId' => $lastItemId,
            'items' => $items,
        ];
    }
}
