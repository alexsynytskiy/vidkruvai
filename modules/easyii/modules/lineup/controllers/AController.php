<?php
namespace yii\easyii\modules\lineup\controllers;

use yii\easyii\components\CategoryController;

class AController extends CategoryController
{
    /** @var string  */
    public $categoryClass = 'yii\easyii\modules\lineup\models\Category';

    /** @var string  */
    public $moduleName = 'lineup';
}