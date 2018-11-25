<?php

namespace app\controllers;

use app\components\Controller;
use yii\easyii\modules\page\api\Page;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        \Yii::$app->seo->setTitle('Головна');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('відкривай, україну');

        return $this->render('index', [
        ]);
    }

    /**
     * @return string
     */
    public function actionQuestions()
    {
        \Yii::$app->seo->setTitle('Поширені питання');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('відкривай, україну');

        return $this->render('questions', [
            'questions' => Page::get(['questions'])
        ]);
    }

    /**
     * @return string
     */
    public function actionContacts()
    {
        \Yii::$app->seo->setTitle('Контакти');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('відкривай, україну');

        return $this->render('contacts', [
            'contacts' => Page::get(['contacts'])
        ]);
    }

    /**
     * @return string
     */
    public function actionAbout()
    {
        \Yii::$app->seo->setTitle('Про проект');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('відкривай, україну');

        return $this->render('about', [
            'about' => Page::get(['about'])
        ]);
    }

    /**
     * @return string
     */
    public function actionError()
    {
        \Yii::$app->seo->setTitle('Проблема');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('відкривай, україну');

        return $this->render('error', [

        ]);
    }
}
