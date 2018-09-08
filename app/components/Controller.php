<?php

namespace app\components;

use app\models\NotificationUser;
use app\models\SiteUser;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * Class Controller
 * @package app\components
 *
 * @property Controller $context
 */
class Controller extends \yii\web\Controller
{
    /**
     * Stores count user's notifications in form of
     * [total => totalAllNotification, category1 => category1Count]
     * @var array
     */
    private $_userNotificationCounters = [];
    /**
     * @var array
     */
    private $_userLastNotifications = [];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

//        $timezone = Yii::$app->timeZone;
//
//        if (!empty(Yii::$app->siteUser->identity->timezone)) {
//            $timezone = Yii::$app->siteUser->identity->timezone;
//        }
//
//        Yii::$app->db->createCommand("SET time_zone=:timeZone", [':timeZone' => $timezone])->execute();
//        Yii::$app->setTimeZone($timezone);

        $this->_setUserLanguage();

        if (!Yii::$app->siteUser->isGuest) {
            $this->_userNotificationCounters = NotificationUser::getUserCountUnreadNotifications(Yii::$app->siteUser->id);

            if (ArrayHelper::getValue($this->_userNotificationCounters, 'total', 0) > 0) {
                $this->_userLastNotifications = NotificationUser::getUserLastNotifications(Yii::$app->siteUser->id);
            }
        }

        Yii::$app->response->headers->add('Cache-Control', 'no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0');
        Yii::$app->response->headers->add('Pragma', 'no-cache');
        Yii::$app->response->headers->add('Expires', 0);
    }

    /**
     * @return array
     */
    public function getUserNotificationCounters()
    {
        return $this->_userNotificationCounters;
    }

    /**
     * @return array
     */
    public function getUserLastNotifications()
    {
        return $this->_userLastNotifications;
    }

    /**
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionGetUserCounters()
    {
        if (!Yii::$app->request->isAjax) {
            throw new BadRequestHttpException('Bad request.');
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'notifications' => $this->_userNotificationCounters,
        ];
    }

    /**
     * @return bool
     */
    private function _setUserLanguage()
    {
        if (!Yii::$app->siteUser->isGuest) {
            if ($language = Yii::$app->request->get('language')) {
                Yii::$app->language = $language;
            } else {
                Yii::$app->language = Yii::$app->siteUser->identity->language;
            }

            $userLang = Yii::$app->siteUser->identity->language;
            $prefLang = Yii::$app->request->getPreferredLanguage();

            return $prefLang !== $userLang ?
                SiteUser::updateUserPreferredLanguage(Yii::$app->siteUser->id, $prefLang) : false;
        }

        return true;
    }
}
