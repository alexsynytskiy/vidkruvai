<?php

namespace app\controllers;

use app\components\AppMsg;
use app\components\BaseDefinition;
use app\components\Controller;
use app\components\events\UserRegisteredEvent;
use app\components\UserRegisteredEventHandler;
use app\models\Achievement;
use app\models\City;
use app\models\definitions\DefLevel;
use app\models\definitions\DefNotification;
use app\models\definitions\DefTeam;
use app\models\definitions\DefTeamSiteUser;
use app\models\definitions\DefUserAchievement;
use app\models\forms\AddSchoolForm;
use app\models\forms\LoginForm;
use app\models\forms\RegisterForm;
use app\models\Level;
use app\models\NotificationUser;
use app\models\search\AchievementSearch;
use app\models\search\LevelSearch;
use app\models\search\NotificationUserSearch;
use app\models\SiteUser;
use app\models\Team;
use app\models\TeamSiteUser;
use app\models\UserAchievement;
use yii\easyii\components\helpers\LanguageHelper;
use yii\easyii\helpers\Image;
use yii\easyii\modules\news\api\News;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/**
 * Class ProfileController
 * @package app\controllers
 */
class ProfileController extends Controller
{
    /**
     * Event name, uses for triggering event when user is registered
     */
    const EVENT_USER_REGISTERED = 'app.controllers.on-user-registered';

    public function init()
    {
        parent::init();

        \Yii::$app->on(self::EVENT_USER_REGISTERED, [new UserRegisteredEventHandler(), 'handle']);
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        \Yii::$app->seo->setTitle('Профіль');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        return $this->renderUserProfilePage(\Yii::$app->siteUser->identity);
    }

    /**
     * @param null $id
     * @return bool|string|Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id = null)
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        $userInfo = SiteUser::getUserRenderingInfo($id);

        return $this->renderUserProfilePage($userInfo['user'], $userInfo['preview']);
    }

    /**
     * @param SiteUser $user
     * @param bool $isPreview
     *
     * @return string
     */
    public function renderUserProfilePage($user, $isPreview = false)
    {
        $levelInfo = $user->getUserLevelInfo();

        $previousLevels = [];
        $nextLevels = Level::getLevels($user->level_id, DefLevel::NEXT_LEVELS);

        if (count($nextLevels) < 2) {
            $previousLevels = Level::getLevels($user->level_id, DefLevel::PREVIOUS_LEVELS,
                2 - count($nextLevels));
        }

        $achievements = Achievement::getAchievementsInProgress($user->id);

        if (count($achievements) < 3) {
            $achievementsToStart = Achievement::getAchievementsToStart($user->id, 3 - count($achievements));
            $achievements = array_merge($achievements, $achievementsToStart);
        }

        if (count($achievements) < 3) {
            $achievementsFinished = Achievement::getAchievementsFinished($user->id, 3 - count($achievements));
            $achievements = array_merge($achievementsFinished, $achievements);
        }

        return $this->render('profile',
            [
                'showUserInfo' => false,
                'preview' => $isPreview,
                'achievements' => $achievements,
                'levelInfo' => $levelInfo,
                'previousLevels' => $previousLevels,
                'nextLevels' => $nextLevels,
                'userCredentials' => SiteUser::getUserCredentials($user),
            ]
        );
    }

    /**
     * @param null $id
     *
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionLevels($id = null)
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        \Yii::$app->seo->setTitle('Рівні');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        $userInfo = SiteUser::getUserRenderingInfo($id);

        $searchModel = new LevelSearch();

        $queryParams = \Yii::$app->request->queryParams;
        $queryParams['LandingLevelSearch']['site_user_id'] = $userInfo['id'];
        $queryParams['LandingLevelSearch']['user_level'] = \Yii::$app->siteUser->identity->level->num;

        $dataProvider = $searchModel->userSearch($queryParams);

        $data = [
            'data' => $dataProvider->getModels(),
            'userCredentials' => SiteUser::getUserCredentials($userInfo['user']),
            'userLevelExperience' => $userInfo['user']->level_experience,
            'userCurrentLevel' => $userInfo['user']->level->num,
            'preview' => $userInfo['preview'],
        ];

        return $this->render('levels', $data);
    }

    /**
     * @param null $id
     *
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionAchievements($id = null)
    {
        $statusUser = $this->checkUserStatus();

        if ($statusUser !== true) {
            return $statusUser;
        }

        \Yii::$app->seo->setTitle('Досягнення');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        $userInfo = SiteUser::getUserRenderingInfo($id);

        $searchModel = new AchievementSearch();

        $queryParams = \Yii::$app->request->queryParams;
        $queryParams['AchievementSearch']['site_user_id'] = $userInfo['id'];

        $dataProvider = $searchModel->userSearch($queryParams);

        $data = [
            'searchModel' => $searchModel,
            'userCredentials' => SiteUser::getUserCredentials($userInfo['user']),
            'preview' => $userInfo['preview'],
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
                        /** @var UserAchievement $status */
                        $status = $group[$i]->getUserAchievementStatus($id ?: \Yii::$app->siteUser->id)->one();

                        if ($status) {
                            if ($status->done === DefUserAchievement::IS_IN_PROGRESS) {
                                $groupStarted = true;

                                if (!$groupStartedFirstPosition) {
                                    $groupStartedFirstPosition = $i;
                                    break;
                                }
                            }

                            if ($i === $groupElements - 1 && $status->done === DefUserAchievement::IS_DONE) {
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

    /**
     * @param string $tag
     * @return bool|string|Response
     */
    public function actionNews($tag = '')
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        \Yii::$app->seo->setTitle('Новини');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        $queryParams = [
            'limit' => \yii\easyii\modules\news\models\News::ITEMS_PER_PAGE + 1,
            'tags' => $tag,
        ];

        if (\Yii::$app->language !== LanguageHelper::LANG_UA) {
            $queryParams = ArrayHelper::merge($queryParams, ['language' => LanguageHelper::LANG_EN]);
        }

        $news = News::items($queryParams);
        $hasToLoadMore = false;
        $lastItemId = 0;

        if (count($news) > \yii\easyii\modules\news\models\News::ITEMS_PER_PAGE) {
            $hasToLoadMore = true;

            array_pop($news);
            $lastItemId = $news[count($news) - 1]->id;
        }

        $params = ArrayHelper::merge(SiteUser::getUserCredentials(\Yii::$app->siteUser ?
            \Yii::$app->siteUser->identity : \Yii::$app->user->identity), [
                'news' => $news,
                'hasToLoadMore' => $hasToLoadMore,
                'lastItemId' => $lastItemId,
                'tag' => $tag,
            ]
        );

        return $this->render('news', $params);
    }

    /**
     * @param string $slug
     * @return bool|string|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public function actionNewsItem($slug = '')
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        if (empty($slug)) {
            return $this->redirect(['news/']);
        }

        $news = News::get([$slug]);

        \yii\easyii\modules\news\models\News::readByIds([$news->id]);

        isset($news->title) ? \Yii::$app->seo->setTitle($news->title) : null;
        isset($news->seo->description) ? \Yii::$app->seo->setDescription($news->seo->description) : null;
        isset($news->seo->keywords) ? \Yii::$app->seo->setKeywords($news->seo->keywords) : null;

        return $this->render('news-view', ['newsItem' => $news]);
    }

    /**
     * @return string|Response
     * @throws BadRequestHttpException
     * @throws \yii\base\Exception
     */
    public function actionAddSchool()
    {
        if (!\Yii::$app->siteUser->isGuest) {
            return $this->redirect(['/profile']);
        }

        if (!\Yii::$app->mutex->acquire('multiple-registration')) {
            \Yii::info('Пользователь попытался выполнить несколько раз подряд регистрацию');

            throw new BadRequestHttpException();
        }

        \Yii::$app->seo->setTitle('Додати школу');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('відкривай, україну');

        $model = new AddSchoolForm();

        if ($model->load(\Yii::$app->request->post()) && $model->add()) {
            $this->flash('success', AppMsg::t('Школу успішно додано!'));

            return $this->redirect(['/register']);
        }

        return $this->render('add-school', [
            'model' => $model,
        ]);
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function actionGetStateCities()
    {
        $errorResponse = ['status' => 'error', 'message' => 'Щось пішло не так..'];

        if (!\Yii::$app->mutex->acquire('multiple-state-select')) {
            \Yii::info('Пользователь попытался выполнить несколько раз подряд вход');

            return $errorResponse;
        }

        try {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            if (!\Yii::$app->request->isPost) {
                throw new BadRequestHttpException();
            }

            $request = \Yii::$app->request;
            $token = $request->post(\Yii::$app->request->csrfParam);

            if (!\Yii::$app->request->validateCsrfToken($token)) {
                throw new BadRequestHttpException();
            }

            $stateId = \Yii::$app->request->post('stateId');
            $cities = City::getList($stateId);

            if($cities) {
                return ['status' => 'success', 'cities' => $cities];
            }

            return ['status' => 'error', 'message' => 'Користувача не знайдено'];
        } catch (BadRequestHttpException $exception) {
            return $errorResponse;
        } catch (\Exception $exception) {
            return $errorResponse;
        }
    }

    /**
     * @param string $hash
     *
     * @return string|Response
     * @throws \Throwable
     */
    public function actionRegister($hash = '')
    {
        if (!\Yii::$app->siteUser->isGuest && $hash === '') {
            return $this->redirect(['/profile']);
        }

        if (!\Yii::$app->mutex->acquire('multiple-registration')) {
            \Yii::info('Пользователь попытался выполнить несколько раз подряд регистрацию');

            throw new BadRequestHttpException();
        }

        \Yii::$app->seo->setTitle('Реєстрація');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('відкривай, україну');

        $model = new RegisterForm();

        $invitationRegistration = false;
        $userTeamItem = null;
        if ($hash) {
            /** @var TeamSiteUser $userTeamItem */
            $userTeamItem = TeamSiteUser::find()
                ->alias('tsu')
                ->innerJoin(Team::tableName() . ' t', 't.id = tsu.team_id')
                ->where([
                    'tsu.hash' => $hash,
                    'tsu.status' => DefTeamSiteUser::STATUS_UNCONFIRMED
                ])
                ->andWhere(['t.status' => [DefTeam::STATUS_UNCONFIRMED, DefTeam::STATUS_ACTIVE]])
                ->one();

            if ($userTeamItem) {
                $userExistsAsUnit = SiteUser::findOne(['email' => $userTeamItem->email]);

                if ($userExistsAsUnit) {
                    $userTeamItem->site_user_id = $userExistsAsUnit->id;
                    $userTeamItem->status = DefTeamSiteUser::STATUS_CONFIRMED;

                    if ($userTeamItem->update()) {
                        $captain = $userTeamItem->team->teamCaptain();

                        \Yii::$app->notification->addToUser($captain, DefNotification::CATEGORY_TEAM,
                            DefNotification::TYPE_TEAM_USER_ACCEPTED, null,
                            ['team_member' => $userExistsAsUnit->getFullName(),
                                'created_at' => date('d-M-Y H:i:s')]);

                        if (\Yii::$app->siteUser->isGuest) {
                            \Yii::$app->siteUser->login($userExistsAsUnit, BaseDefinition::getSessionExpiredTime());
                        }

                        $this->flash('success', AppMsg::t('Запрошення успішно прийнято!'));

                        if ($userExistsAsUnit->agreement_read) {
                            return $this->redirect(['/team']);
                        }

                        return $this->redirect('/rules');
                    }
                }

                $model->email = $userTeamItem->email;
                $invitationRegistration = true;
            }
        }

        if ($model->load(\Yii::$app->request->post()) && $model->register()) {
            \Yii::$app->siteUser->login($model->getUser(), BaseDefinition::getSessionExpiredTime());

            $user = \Yii::$app->siteUser->identity;
            $user->updateLoginCount();

            \Yii::$app->notification->addToUser($user, DefNotification::CATEGORY_ACCOUNT,
                DefNotification::TYPE_HELLO_USER, null, []);

            $userEvent = new UserRegisteredEvent();
            $userEvent->userId = $user->id;
            \Yii::$app->trigger(self::EVENT_USER_REGISTERED, $userEvent);

            if ($invitationRegistration && $userTeamItem) {
                $userTeamItem->site_user_id = $user->id;
                $userTeamItem->status = DefTeamSiteUser::STATUS_CONFIRMED;

                if ($userTeamItem->update()) {
                    $captain = $userTeamItem->team->teamCaptain();

                    \Yii::$app->notification->addToUser($captain, DefNotification::CATEGORY_TEAM,
                        DefNotification::TYPE_TEAM_USER_ACCEPTED, null,
                        ['team_member' => $user->getFullName(), 'created_at' => date('d-M-Y H:i:s')]);
                }
            }

            if ($user->login_count === 1 && !$user->agreement_read) {
                return $this->redirect('/rules');
            }

            return $this->redirect(['/profile']);
        }

        return $this->render('register', [
            'model' => $model,
            'invitation' => $invitationRegistration,
        ]);
    }

    /**
     * @param string $hash
     * @param string $type
     *
     * @return string|Response
     * @throws \Throwable
     */
    public function actionDecline($type = '', $hash = '')
    {
        if (!\Yii::$app->siteUser->isGuest) {
            return $this->redirect(['/profile']);
        }

        if (!\Yii::$app->mutex->acquire('multiple-unsubscribe')) {
            \Yii::info('Пользователь попытался отказаться от приглашения несколько раз');

            throw new BadRequestHttpException();
        }

        if ($hash && $type) {
            $userTeamItem = TeamSiteUser::find()
                ->alias('tsu')
                ->innerJoin(Team::tableName() . ' t', 't.id = tsu.team_id')
                ->where([
                    'tsu.hash' => $hash,
                    'tsu.status' => DefTeamSiteUser::STATUS_UNCONFIRMED
                ])
                ->andWhere(['t.status' => [DefTeam::STATUS_UNCONFIRMED, DefTeam::STATUS_ACTIVE]])
                ->one();

            if ($userTeamItem) {
                $userTeamItem->status = DefTeamSiteUser::getStatusByType($type);

                if ($userTeamItem->update()) {
                    $captain = $userTeamItem->team->teamCaptain();

                    \Yii::$app->notification->addToUser($captain, DefNotification::CATEGORY_TEAM,
                        DefNotification::TYPE_TEAM_USER_CANCELLED, null,
                        ['team_member' => $userTeamItem->email, 'created_at' => date('d-M-Y H:i:s')]);

                    $this->flash('success', AppMsg::t('Запрошення успішно відхилено!'));
                } else {
                    $this->flash('error', AppMsg::t('Щось пішло не так..'));
                }
            }
            else {
                $this->flash('error', AppMsg::t('Запрошення для користувача не існує'));
            }
        }
        else {
            $this->flash('error', AppMsg::t('Недостатньо параметрів'));
        }

        return $this->redirect(['/']);
    }

    /**
     * @return string|\yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            \Yii::$app->user->logout();
        }

        if (!\Yii::$app->siteUser->isGuest && \Yii::$app->siteUser->identity->agreement_read) {
            return $this->redirect(['/profile']);
        }

        if (!\Yii::$app->mutex->acquire('multiple-login')) {
            \Yii::info('Пользователь попытался выполнить несколько раз подряд вход');

            throw new BadRequestHttpException();
        }

        \Yii::$app->seo->setTitle('Вхід');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        $model = new LoginForm();

        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            $user = \Yii::$app->siteUser->identity;
            $user->updateLoginCount();

            if ($user->agreement_read) {
                return $this->redirect(['/profile']);
            }

            return $this->redirect('/rules');
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * @return array|bool|string|Response
     *
     * @throws BadRequestHttpException
     * @throws \Throwable
     */
    public function actionUpdateProfile()
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        if (!\Yii::$app->mutex->acquire('multiple-profile-update')) {
            \Yii::info('Пользователь попытался несколько раз обновить профиль');

            throw new BadRequestHttpException();
        }

        \Yii::$app->seo->setTitle('Редагуати профіль');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        $model = SiteUser::findIdentity(\Yii::$app->siteUser->identity->id);

        if ($model && $model->load(\Yii::$app->request->post())) {
            if (\Yii::$app->request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if (isset($_FILES)) {
                $model->avatar = UploadedFile::getInstance($model, 'avatar');
                if ($model->avatar && $model->validate(['avatar'])) {
                    $model->avatar = Image::upload($model->avatar, 'siteusers');
                } else {
                    $model->avatar = $model->oldAttributes['avatar'];
                }
            }

            if ($model->update()) {
                $this->flash('success', AppMsg::t('Профайл користувача змінено'));
            } else {
                $this->flash('error', AppMsg::t('Профайл не змінено через внутрішню помилку'));
            }
            return $this->refresh();
        }

        return $this->render('update-profile', [
            'model' => $model
        ]);
    }

    /**
     * @param string $category
     * @param string $status
     *
     * @return string
     */
    public function actionNotifications($category = '', $status = '')
    {
        $listCategories = DefNotification::getListCategories();

        if (empty($category) || !array_key_exists($category, $listCategories)) {
            $category = 'all';
        }

        $searchModel = new NotificationUserSearch();

        $searchModel->userId = \Yii::$app->siteUser->id;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        $userCategories = NotificationUser::getUserCategories(\Yii::$app->siteUser->id);

        $data = [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'category' => $category,
            'status' => $status,
            'userCategories' => $userCategories,
            'listCategories' => $listCategories,
        ];

        if (\Yii::$app->request->isAjax) {
            return $this->renderAjax('/notifications/index', $data);
        }

        return $this->render('/notifications/index', $data);
    }

    /**
     * @return string
     */
    public function actionHelp()
    {
        \Yii::$app->seo->setTitle('Техпідтримка');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        return $this->render(\Yii::$app->siteUser->isGuest ? 'help-guest' : 'help-logged-in');
    }

    /**
     * @return string
     */
    public function actionRules()
    {
        if (\Yii::$app->siteUser->isGuest) {
            return $this->redirect('/login');
        }

        \Yii::$app->seo->setTitle('Правила');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        return $this->render('rules');
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function actionAgreement()
    {
        $errorResponse = ['status' => 'error', 'message' => 'Щось пішло не так..'];

        if (!\Yii::$app->mutex->acquire('multiple-agreement')) {
            \Yii::info('Пользователь попытался выполнить несколько раз подряд вход');

            return $errorResponse;
        }

        try {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            if (!\Yii::$app->request->isPost || \Yii::$app->siteUser->isGuest) {
                throw new BadRequestHttpException();
            }

            $request = \Yii::$app->request;
            $token = $request->post(\Yii::$app->request->csrfParam, '');

            if (!\Yii::$app->request->validateCsrfToken($token)) {
                throw new BadRequestHttpException();
            }

            $user = \Yii::$app->siteUser->identity;

            if ($user && !$user->agreement_read) {
                $user->agreement_read = SiteUser::AGREEMENT_READ;
                if (!$user->update()) {
                    return $errorResponse;
                }
            }

            return ['status' => 'success', 'profileUrl' => '/profile'];
        } catch (BadRequestHttpException $exception) {
            return $errorResponse;
        } catch (\Exception $exception) {
            return $errorResponse;
        }
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        \Yii::$app->siteUser->logout();

        return $this->redirect(['/']);
    }
}
