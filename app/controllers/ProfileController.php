<?php

namespace app\controllers;

use app\components\AppMsg;
use app\components\BaseDefinition;
use app\models\forms\LoginForm;
use app\models\forms\RegisterForm;
use app\models\SiteUser;
use yii\captcha\CaptchaAction;
use yii\easyii\components\helpers\LanguageHelper;
use yii\easyii\helpers\Image;
use yii\easyii\models\Admin;
use yii\easyii\modules\news\api\News;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

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

        if (\Yii::$app->language !== LanguageHelper::LANG_UA) {
            $news = News::items(['limit' => 6, 'language' => 'en', 'tags' => \Yii::$app->request->get('tag')]);
        } else {
            $news = News::items(['limit' => 6, 'tags' => \Yii::$app->request->get('tag')]);
        }

        $showLoadMore = false;
        if (count($news) > 6) {
            $showLoadMore = true;
            array_pop($news);
        }

        $tag = \Yii::$app->request->get('tag');

        $params = ArrayHelper::merge($this->testDataUser(), [
            'news' => $news,
            'showLoadMore' => $showLoadMore,
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
     * @return array|bool|string|\yii\web\Response
     * @throws \yii\web\HttpException
     */
    public function actionUpdateProfile()
    {
        $status = $this->checkUserStatus();

        if ($status !== true) {
            return $status;
        }

        \Yii::$app->seo->setTitle('Редагуати профіль');
        \Yii::$app->seo->setDescription('Відкривай Україну');
        \Yii::$app->seo->setKeywords('Відкривай, Україну');

        $model = SiteUser::findIdentity(\Yii::$app->siteUser->identity->id);

        if ($model && $model->load(\Yii::$app->request->post())) {
            if (\Yii::$app->request->isAjax) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
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
