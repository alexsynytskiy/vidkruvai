<?php

namespace app\controllers;

use app\components\AppMsg;
use app\components\Controller;
use app\models\Category;
use app\models\definitions\DefStoreItem;
use app\models\definitions\DefTeam;
use app\models\Sale;
use app\models\StoreItem;
use app\models\WrittenTaskAnswer;
use yii\db\Query;

/**
 * Class ProgressController
 * @package app\controllers
 */
class ProgressController extends Controller
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

        \Yii::$app->seo->setTitle('Прогрес');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        $team = \Yii::$app->siteUser->identity->team;

        if(!$team) {
            $this->flash('error', AppMsg::t('Щоб виконувати завдання необхідно долучитись до команди!'));
            return $this->redirect('/profile');
        }

        if($team->status === DefTeam::STATUS_UNCONFIRMED) {
            $this->flash('error', AppMsg::t('Ми перевіряємо склад вашої команди та відповідність правилам! Зачекай трохи, та повертайся.'));
            return $this->redirect('/team');
        }

        if($team->status === DefTeam::STATUS_DISABLED) {
            $this->flash('error', AppMsg::t('На жаль ваша команда не пройшла перший етап. Повертайтесь наступного року!'));
            return $this->redirect('/team');
        }

        $categories = Category::find()->storeCategory()->all();
        $data = [];

        /** @var Category $category */
        foreach ($categories as $category) {
            $data[$category->name] = $category->childrenSubItemsBoughtCount();
        }

        $saleDataSchool = Sale::getSalesByType(DefStoreItem::TYPE_SCHOOL, $team->id);

        $saleDataCity = Sale::getSalesByType(DefStoreItem::TYPE_CITY, $team->id);

        return $this->render('index',
            [
                'data' => $data,
                'saleDataSchool' => $saleDataSchool,
                'saleDataCity' => $saleDataCity,
            ]
        );
    }
}
