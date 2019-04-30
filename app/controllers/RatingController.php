<?php

namespace app\controllers;

use app\components\AppMsg;
use app\components\Controller;
use app\components\helpers\MapHelper;
use app\components\helpers\RatingHelper;
use app\models\Category;
use app\models\definitions\DefStoreItem;
use app\models\definitions\DefTeam;
use app\models\Sale;
use app\models\State;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class RatingController
 * @package app\controllers
 */
class RatingController extends Controller
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

        if(!in_array(\Yii::$app->siteUser->identity->email, [
            'parasolkailb@gmail.com',
            'mariiapanchenko@gmail.com',
            'fr@coukraine.org',
            'm.panchenko@coukraine.org',
            'relleka@ukr.net',
            'a.matviienko@coukraine.org',
            'n.netreba@coukraine.org',
            'nmnetreba@gmail.com',
            'vidkryvai.ukrainu@gmail.com',
            'v.ilyina@ukr.net',
            'alionaculturerazom@gmail.com',
            'Svitpustova@gmail.com',
            'alexsynytskiy@ukr.net',
            'denbooker@gmail.com',
            'dzuibloyaroslava@gmail.com',
            'dashasmr2002@gmail.com',
            'cawakovalenko7@gmail.com',
            'belks887@gmail.com'
        ])) {
            $this->flash('error', AppMsg::t('Розділ поки що не доступний'));
            return $this->redirect('/profile');
        }

        \Yii::$app->seo->setTitle('Рейтинг');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        $team = \Yii::$app->siteUser->identity->team;

        if (!$team) {
            $this->flash('error', AppMsg::t('Щоб виконувати завдання необхідно долучитись до команди!'));
            return $this->redirect('/profile');
        }

        if ($team->status === DefTeam::STATUS_UNCONFIRMED) {
            $this->flash('error', AppMsg::t('Ми перевіряємо склад вашої команди та відповідність правилам! Зачекай трохи, та повертайся.'));
            return $this->redirect('/team');
        }

        if ($team->status === DefTeam::STATUS_DISABLED) {
            $this->flash('error', AppMsg::t('На жаль ваша команда не пройшла перший етап. Повертайтесь наступного року!'));
            return $this->redirect('/team');
        }

        $saleDataCity = Sale::getSalesByType(DefStoreItem::TYPE_CITY, $team->id);
        $marksJS = MapHelper::prepareMarks();

        $categories = Category::find()->storeCategory()->all();
        $states = State::find()->all();

        $statesRating = RatingHelper::prepareStatesRating($states, $categories);
        $statesRatingCounted = RatingHelper::sortStatesByRating($statesRating, $categories);

        $stateData = MapHelper::prepareRegions($this, $statesRatingCounted, $categories);

        return $this->render('index',
            [
                'saleDataCity' => $saleDataCity,
                'marksJS' => $marksJS,
                'stateData' => Json::encode($stateData),
                'categories' => $categories,
                'statesRating' => $statesRatingCounted,
            ]
        );
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionRenderCityProgress()
    {
        if (!\Yii::$app->request->isPost || !\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;

        $cityId = (int)\Yii::$app->request->post('cityId');

        if (!$cityId) {
            return [];
        }

        $errors = [];
        $progress = '';

        try {
            $categories = Category::find()->storeCategory()->all();

            $progress = $this->renderPartial('city-infrastructure', [
                'categories' => $categories,
                'cityId' => $cityId,
            ]);
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        if (empty($errors)) {
            return ArrayHelper::merge([
                'status' => 'success',
            ], ['progress' => $progress]);
        }

        return [
            'status' => 'error', 'message' => implode(', ', $errors),
        ];
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionRenderStateProgress()
    {
        if (!\Yii::$app->request->isPost || !\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;

        $stateId = (int)\Yii::$app->request->post('stateId');

        if (!$stateId) {
            return [];
        }

        $errors = [];
        $rating = '';

        try {
            $categories = Category::find()->storeCategory()->all();
            $states = State::find()->all();

            $statesRating = RatingHelper::prepareStatesRating($states, $categories);
            $statesRatingCounted = RatingHelper::sortStatesByRating($statesRating, $categories);

            $rating = $this->renderPartial('rating-table', [
                'categories' => $categories,
                'statesRating' => $statesRatingCounted,
                'stateId' => $stateId,
            ]);
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
        }

        if (empty($errors)) {
            return ArrayHelper::merge([
                'status' => 'success',
            ], ['rating' => $rating]);
        }

        return [
            'status' => 'error', 'message' => implode(', ', $errors),
        ];
    }
}
