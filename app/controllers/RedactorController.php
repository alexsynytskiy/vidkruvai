<?php

namespace app\controllers;

use app\components\Controller;
use yii\easyii\helpers\Image;
use yii\web\HttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class RedactorController extends Controller
{
    public $controllerNamespace = 'yii\redactor\controllers';
    public $defaultRoute = 'upload';
    public $uploadDir = '@webroot/uploads';
    public $uploadUrl = '/uploads';

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON
                ],
            ]
        ];
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * @param string $dir
     * @return array
     * @throws HttpException
     */
    public function actionUpload($dir = '')
    {
        $fileInstance = UploadedFile::getInstanceByName('file');

        if ($fileInstance) {
            $file = Image::upload($fileInstance, $dir);
            if ($file) {
                return $this->getResponse($file);
            }
        }
        return ['error' => 'Не вдалося перевірити передані дані.'];
    }

    /**
     * @param $fileName
     * @return array
     */
    private function getResponse($fileName)
    {
        return [
            'filelink' => $fileName,
            'filename' => basename($fileName)
        ];
    }
}