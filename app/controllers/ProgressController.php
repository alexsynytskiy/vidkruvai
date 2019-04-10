<?php

namespace app\controllers;

use app\components\AppMsg;
use app\components\Controller;
use app\models\Category;
use app\models\definitions\DefTeam;
use app\models\Sale;
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

        $saleData = Sale::find()->where(['team_id' => $team->id])->all();

        $executedTasksData = (new Query)->from(WrittenTaskAnswer::tableName())->select(['MONTHNAME(updated_at) as month', 'count(id) value'])
            ->where(['team_id' => $team->id])->andWhere(['!=', 'text', ['', null]])->groupBy(['MONTH(updated_at)'])->all();

        foreach ($executedTasksData as $monthData) {
            $monthData['month'] = \Yii::t('app', $monthData['month']);
        }

        return $this->render('index',
            [
                'data' => $data,
                'saleData' => $saleData,
                'executedTasksData' => $executedTasksData,
            ]
        );
    }
}
