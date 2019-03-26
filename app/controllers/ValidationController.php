<?php

namespace app\controllers;

use app\components\CaptchaAction;
use app\components\Controller;

/**
 * Class ValidationController
 * @package app\controllers
 */
class ValidationController extends Controller
{
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => CaptchaAction::className(),
                'height' => 50,
                'maxLength' => 4,
                'minLength' => 4
            ],
        ];
    }
}
