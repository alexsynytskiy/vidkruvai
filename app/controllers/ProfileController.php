<?php

namespace app\controllers;

use app\components\AppMsg;
use app\components\BaseDefinition;
use app\models\forms\LoginForm;
use app\models\forms\RegisterForm;
use app\models\SiteUser;
use yii\captcha\CaptchaAction;
use yii\easyii\components\helpers\LanguageHelper;
use yii\easyii\models\Admin;
use yii\easyii\modules\news\api\News;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Class ProfileController
 * @package app\controllers
 */
class ProfileController extends Controller
{
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

    public function testDataUser()
    {
        $params = [
            'name' => '',
            'email' => '',
        ];

        if (!\Yii::$app->siteUser->isGuest) {
            /** @var SiteUser|Admin $user */
            $user = \Yii::$app->siteUser->identity ?: \Yii::$app->user->identity;

            if ($user instanceof SiteUser) {
                $params = [
                    'name' => $user->name . ' ' . $user->surname,
                    'email' => $user->email,
                ];
            } else {
                $params = [
                    'name' => $user->username,
                    'email' => '',
                ];
            }
        }

        return $params;
    }

    /**
     * @return string
     */
    public function actionHelp()
    {
        \Yii::$app->seo->setTitle('Техпідтримка');
        \Yii::$app->seo->setDescription('Intellias quiz');
        \Yii::$app->seo->setKeywords('intellias, quiz');

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
        \Yii::$app->seo->setDescription('Intellias quiz');
        \Yii::$app->seo->setKeywords('intellias, quiz');

        return $this->render('rules');
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

        $news = null;

        if(\Yii::$app->language !== LanguageHelper::LANG_UA) {
            $news = News::items(['limit' => 6, 'language' => 'en', 'tags' => \Yii::$app->request->get('tag')]);
        }
        else {
            $news = News::items(['limit' => 6, 'tags' => \Yii::$app->request->get('tag')]);
        }

        $showLoadMore = false;
        if(count($news) > 6) {
            $showLoadMore = true;
            array_pop($news);
        }

        $tag = \Yii::$app->request->get('tag');

        $params = ArrayHelper::merge($this->testDataUser(), [
            'news'         => $news,
            'showLoadMore' => $showLoadMore,
            'tag'          => $tag,
        ]);

        return $this->render('news', $params);
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
        \Yii::$app->seo->setDescription('Intellias quiz');
        \Yii::$app->seo->setKeywords('intellias, quiz');

        $params = ArrayHelper::merge($this->testDataUser(), [
        ]);

        return $this->render('profile', $params);
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

            \Yii::$app->siteUser->identity->updateLoginCount();

            if (\Yii::$app->siteUser->identity->login_count === 1) {
                //QuestionsSetter::setUserQuestions();

                if (!\Yii::$app->siteUser->identity->agreement_read) {
                    return $this->redirect('/rules');
                }
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
     * @return bool|string|\yii\web\Response
     */
    public function actionUpdateProfile() {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        $model = SiteUser::findIdentity(\Yii::$app->siteUser->identity->id);

        if($model && $model->load(\Yii::$app->request->post())) {
            try {
                if($this->_saveUser($model)) {

                    return $this->redirect(['edit']);
                }
            }
            catch(\Exception $e) {
                \Yii::error("Произошла ошибка при обновлении профиля.\nError: " . $e->getMessage());
            }
        }

        return $this->render('update-profile', [
            'model' => $model
        ]);
    }

    /**
     * @param SiteUser $model
     *
     * @return bool
     */
    private function _saveUser($model) {
        if($model->validate()) {
            if($model->save(false)) {
                \Yii::$app->session->setFlash('success', AppMsg::t('Изменения успешно сохраненны.'));

                return true;
            }
        }

        \Yii::$app->session->setFlash('error', AppMsg::t('При заполнении формы были допущены ошибки. Изменения не сохранены.'));

        return false;
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        \Yii::$app->siteUser->logout();

        return $this->redirect(['site/index']);
    }
}
