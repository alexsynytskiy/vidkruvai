<?php

namespace app\components;

use app\components\helpers\EntityHelper;
use app\models\Achievement;
use app\models\definitions\DefEntityAchievement;
use app\models\EntityAchievement;
use app\models\NotificationUser;
use app\models\search\AchievementSearch;
use app\models\search\LevelSearch;
use app\models\SiteUser;
use app\models\Team;
use Yii;
use yii\base\ErrorException;
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

    public static $entityType = '';
    public static $entityLevelNum = '';

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
     * @param $mode
     * @return bool
     */
    public static function initMode($mode)
    {
        if ($mode === DefEntityAchievement::ENTITY_USER) {
            self::$entityType = DefEntityAchievement::ENTITY_USER;
            self::$entityLevelNum =  \Yii::$app->siteUser->identity->level->num;
        }

        if ($mode === DefEntityAchievement::ENTITY_TEAM) {
            self::$entityType = DefEntityAchievement::ENTITY_TEAM;
            self::$entityLevelNum =  \Yii::$app->siteUser->identity->team->level->num;
        }

        return true;
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

    /**
     * @param null $id
     * @return bool|string|Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionLevels($id = null)
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        $entityInfo = EntityHelper::getEntityRenderingInfo($id, self::$entityType);

        $searchModel = new LevelSearch();

        $queryParams = \Yii::$app->request->queryParams;
        $queryParams['LevelSearch']['entity_id'] = $entityInfo['id'];
        $queryParams['LevelSearch']['entity_level'] = self::$entityLevelNum;
        $queryParams['LevelSearch']['entity_type'] = self::$entityType;

        $dataProvider = $searchModel->userSearch($queryParams);

        $data = [
            'data' => $dataProvider->getModels(),
            'entityCredentials' => EntityHelper::getEntityCredentials(self::$entityType, $entityInfo['id']),
            'entityLevelExperience' => $entityInfo['entity']->level_experience,
            'entityCurrentLevel' => $entityInfo['entity']->level->num,
            'preview' => $entityInfo['preview'],
        ];

        return $this->render('levels', $data);
    }

    /**
     * @param null $id
     * @return bool|string|Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionAchievements($id = null)
    {
        $statusUser = $this->checkUserStatus();

        if ($statusUser !== true) {
            return $statusUser;
        }

        $entityInfo = EntityHelper::getEntityRenderingInfo($id, self::$entityType);

        $searchModel = new AchievementSearch();

        $queryParams = \Yii::$app->request->queryParams;
        $queryParams['AchievementSearch']['entity_id'] = $entityInfo['id'];
        $queryParams['AchievementSearch']['entity_type'] = self::$entityType;

        $dataProvider = $searchModel->userSearch($queryParams);

        $data = [
            'searchModel' => $searchModel,
            'entityCredentials' => EntityHelper::getEntityCredentials(self::$entityType, $entityInfo['id']),
            'preview' => $entityInfo['preview'],
            'status' => array_key_exists('filterAchievementType', $queryParams['AchievementSearch']) ?
                $queryParams['AchievementSearch']['filterAchievementType'] : null
        ];

        $getParams = \Yii::$app->request->get();

        if (isset($getParams['id'])) {
            unset($getParams['id']);
        }

        if (!$getParams) {
            $achievements = $dataProvider->getModels();

            $category = null;
            $groups = [];

            foreach ($achievements as $achievement) {
                if (($category && $category !== $achievement->group_id) || empty($category)) {
                    $category = $achievement->group_id;
                }

                $groups[($achievement->group->slug ?: '') . '__' . ($achievement->group->name ?: '')][] = $achievement;
            }

            /**
             * @var string $key
             * @var  Achievement[] $group
             */
            foreach ($groups as $key => $group) {
                $groupStarted = false;
                $groupStartedFirstPosition = null;
                $groupDone = false;
                $groupElements = count($group);

                if ($groupElements > 3) {
                    for ($i = 0; $i < $groupElements; $i++) {
                        /** @var EntityAchievement $status */
                        $status = $group[$i]->getEntityAchievementStatus($id ?:
                            (self::$entityType === DefEntityAchievement::ENTITY_USER ? \Yii::$app->siteUser->id : \Yii::$app->siteUser->identity->team->id),
                            self::$entityType)->one();

                        if ($status) {
                            if ($status->done === DefEntityAchievement::IS_IN_PROGRESS) {
                                $groupStarted = true;

                                if (!$groupStartedFirstPosition) {
                                    $groupStartedFirstPosition = $i;
                                    break;
                                }
                            }

                            if ($i === $groupElements - 1 && $status->done === DefEntityAchievement::IS_DONE) {
                                $groupDone = true;
                            }
                        }
                    }

                    if ($groupStarted && !in_array($groupStartedFirstPosition, [$groupElements - 1,
                            $groupElements - 2], false)) {
                        $groups[$key]['preview'] = [$group[$groupStartedFirstPosition],
                            $group[++$groupStartedFirstPosition], $group[++$groupStartedFirstPosition]];
                    } else {
                        $position = null;

                        if ((!$groupDone && !$groupStarted) || ($groupStarted && $groupStartedFirstPosition === 0)) {
                            $position = 0;
                        } elseif ($groupDone || ($groupStarted && in_array($groupStartedFirstPosition,
                                    [$groupElements - 1, $groupElements - 2], false))) {
                            $position = $groupElements - 3;
                        }

                        $groups[$key]['preview'] = [$group[$position], $group[++$position], $group[++$position]];
                    }

                    $groups[$key]['full'] = $group;
                } else {
                    $groups[$key]['preview'] = $group;
                    $groups[$key]['full'] = [];
                }
            }

            $data = array_merge($data, ['groups' => $groups]);

            return $this->render('achievements-cropped', $data);
        }

        $data = array_merge($data, ['dataProvider' => $dataProvider]);

        return $this->render('achievements', $data);
    }
}
