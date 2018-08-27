<?php

namespace app\models\forms;

use app\components\BaseDefinition;
use app\components\DataException;
use app\models\definitions\DefSiteUser;
use app\models\SiteUser;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $password;
    /**
     * @var SiteUser
     */
    private $_user;
    /**
     * @var string
     */
    public $captchaUser;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password', 'captchaUser'], 'required'],
            ['captchaUser', 'captcha', 'captchaAction' => '/site/captcha'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Електронна пошта',
            'password' => 'Пароль',
            'captchaUser' => 'Капча',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            $errorMsg = 'Пошта чи пароль введені з помилкою.';

            try {
                if (!$user) {
                    throw new DataException($errorMsg);
                }

                if (empty($user->password)) {
                    throw new DataException($errorMsg);
                }

                if (!$user->validatePassword($this->password)) {
                    throw new DataException($errorMsg);
                }
            } catch (DataException $e) {
                $this->addError($attribute, $e->getMessage());
            }
        }
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function checkStatus($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            $errorMsg = 'Вхід заборонено';

            try {
                if (!$user) {
                    throw new DataException($errorMsg);
                }

                if ($user->status === DefSiteUser::STATUS_BLOCKED) {
                    throw new DataException($errorMsg);
                }
            } catch (DataException $e) {
                $this->addError($attribute, $e->getMessage());
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->siteUser->login($this->getUser(), BaseDefinition::getSessionExpiredTime());
        }

        return false;
    }

    /**
     * Finds user by [[nickname]]
     *
     * @return SiteUser|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = SiteUser::findIdentityByNick($this->email);
        }

        return $this->_user;
    }
}