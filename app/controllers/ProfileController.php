<?php

namespace app\controllers;

use app\components\BaseDefinition;
use app\components\Controller;
use app\components\events\UserRegisteredEvent;
use app\components\UserRegisteredEventHandler;
use app\models\Achievement;
use app\models\definitions\DefLevel;
use app\models\definitions\DefNotification;
use app\models\forms\LoginForm;
use app\models\forms\RegisterForm;
use app\models\forms\TeamCreateForm;
use app\models\Level;
use app\models\NotificationUser;
use app\models\search\NotificationUserSearch;
use app\models\SiteUser;
use yii\captcha\CaptchaAction;
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
                'minLength' => 4,
            ],
        ];
    }

    /**
     * @return bool|\yii\web\Response
     */
    private function checkUserStatus()
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
     * @return string
     */
    public function actionNews()
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        \Yii::$app->seo->setTitle('Новини');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        $tag = \Yii::$app->request->get('tag');

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
        ]);

        return $this->render('news', $params);
    }

    /**
     * @param null $slug
     * @return bool|string|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public function actionNewsItem($slug = null)
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        if (!$slug) {
            return $this->redirect(['news/']);
        }

        $news = null;

        if (\Yii::$app->language !== LanguageHelper::LANG_UA) {
            $news = News::get([$slug, 'en']);
        } else {
            $news = News::get([$slug]);
        }

        \yii\easyii\modules\news\models\News::readByIds([$news->id]);

        isset($news->title) ? \Yii::$app->seo->setTitle($news->title) : null;
        isset($news->seo->description) ? \Yii::$app->seo->setDescription($news->seo->description) : null;
        isset($news->seo->keywords) ? \Yii::$app->seo->setKeywords($news->seo->keywords) : null;

        return $this->render('view', ['news' => $news]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionRegister()
    {
        if (!\Yii::$app->siteUser->isGuest) {
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

        if ($model->load(\Yii::$app->request->post()) && $model->register()) {
            \Yii::$app->siteUser->login($model->getUser(), BaseDefinition::getSessionExpiredTime());

            $user = \Yii::$app->siteUser->identity;
            $user->updateLoginCount();

            \Yii::$app->notification->addToUser($user, DefNotification::CATEGORY_ACCOUNT,
                DefNotification::TYPE_HELLO_USER, null, []);

            $userEvent = new UserRegisteredEvent();
            $userEvent->userId = $user->id;
            \Yii::$app->trigger(self::EVENT_USER_REGISTERED, $userEvent);

            if ($user->login_count === 1 && !$user->agreement_read) {
                return $this->redirect('/rules');
            }

            return $this->redirect(['/profile']);
        }

        return $this->render('register', [
            'model' => $model,
        ]);
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
            if (\Yii::$app->siteUser->identity->agreement_read) {
                return $this->redirect(['/profile']);
            }

            return $this->redirect('/rules');
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * @return array|bool|string|\yii\web\Response
     * @throws \yii\web\HttpException
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

            if ($model->save()) {
                $this->flash('success', \Yii::t('easyii', 'User updated'));
            } else {
                $this->flash('error', \Yii::t('easyii', 'Update error. {0}', $model->getErrors()));
            }
            return $this->refresh();
        }

        return $this->render('update-profile', [
            'model' => $model
        ]);
    }

    /**
     * @return array|bool|string|Response
     * @throws \yii\db\Exception
     * @throws \yii\web\HttpException
     */
    public function actionCreateTeam()
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        if (!\Yii::$app->mutex->acquire('multiple-team-creation')) {
            \Yii::info('Пользователь попытался несколько раз создать команду');

            throw new BadRequestHttpException();
        }

        \Yii::$app->seo->setTitle('Створити команду');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        $model = new TeamCreateForm;

        if ($model && $model->load(\Yii::$app->request->post())) {
            if (\Yii::$app->request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if (isset($_FILES)) {
                $model->avatar = UploadedFile::getInstance($model, 'avatar');
                if ($model->avatar && $model->validate(['avatar'])) {
                    $model->avatar = Image::upload($model->avatar, 'team');
                } else {
                    $model->avatar = $model->oldAttributes['avatar'];
                }
            }

            if ($model->createTeam()) {
                $this->flash('success', \Yii::t('easyii', 'User updated'));
            } else {
                $this->flash('error', \Yii::t('easyii', 'Update error. {0}', $model->getErrors()));
            }
            return $this->refresh();
        }

        return $this->render('team-create', [
            'model' => $model
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionClearImage($id)
    {
        $model = SiteUser::findOne($id);

        if ($model === null) {
            $this->flash('error', \Yii::t('easyii', 'Not found'));
        } else {
            $model->avatar = '';
            if ($model->update()) {
                @unlink(\Yii::getAlias('@webroot') . $model->avatar);
                $this->flash('success', \Yii::t('easyii', 'Image cleared'));
            } else {
                $this->flash('error', \Yii::t('easyii', 'Update error. {0}', $model->getErrors()));
            }
        }
        return $this->back();
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
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        \Yii::$app->siteUser->logout();

        return $this->redirect(['site/index']);
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

    public function back()
    {
        return $this->redirect(\Yii::$app->request->referrer);
    }
}
