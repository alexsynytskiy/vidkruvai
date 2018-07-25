<?php
namespace yii\easyii\controllers;

use yii\easyii\modules\lineup\models\Category;
use yii\easyii\modules\news\api\News;
use yii\helpers\StringHelper;

class DefaultController extends \yii\easyii\components\Controller
{
    public function actionIndex()
    {
        $news = [];

        return $this->render('index', [
            'news'    => $news
        ]);
    }
}