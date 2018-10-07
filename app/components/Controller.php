<?php

namespace app\components;

use app\models\NotificationUser;
use app\models\SiteUser;
use app\models\Team;
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

    /**
     * @return bool|\yii\web\Response
     */
    public function checkUserStatus()
    {
        $user = \Yii::$app->siteUser;

        if ($user->isGuest) {
            return $this->redirect('/login');
        }

        if (!$user->identity->agreement_read) {
            return $this->redirect('/rules');
        }

        return true;
    }

    /**
     * Write in sessions alert messages
     * @param string $type error or success
     * @param string $message alert body
     */
    public function flash($type, $message)
    {
        \Yii::$app->getSession()->setFlash($type === 'error' ? 'danger' : $type, $message);
    }

    /**
     * @return Response
     */
    public function back()
    {
        return $this->redirect(\Yii::$app->request->referrer);
    }

    /**
     * @param int $id
     * @param string $className
     *
     * @return \yii\web\Response
     * @throws \Throwable
     */
    public function actionClearImage($id, $className)
    {
        if (\Yii::$app->siteUser->isGuest) {
            throw new BadRequestHttpException();
        }

        $classNameFull = '';

        if ($className === 'siteuser') {
            $classNameFull = SiteUser::className();
        } elseif ($className === 'team') {
            $classNameFull = Team::className();
        }
        /** @var SiteUser|Team $model */
        $model = $classNameFull::findOne($id);

        if ($model === null) {
            $this->flash('error', \Yii::t('easyii', 'Not found'));
        } else {
            $model->avatar = '';
            if ($model->update()) {
                @unlink(\Yii::getAlias('@webroot') . $model->avatar);
                $this->flash('success', AppMsg::t('Зображення видалено'));
            } else {
                $this->flash('error', AppMsg::t('Зображення не видалено через внутрішню помлку'));
            }
        }
        return $this->back();
    }
}
