<?php

namespace app\models\forms;

use app\models\SiteUser;
use Yii;
use yii\base\Model;

/**
 * Register form
 */
class RegisterForm extends Model
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $surname;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $role;
    /**
     * @var string
     */
    public $age;
    /**
     * @var string
     */
    public $class;
    /**
     * @var string
     */
    public $school;
    /**
     * @var string
     */
    public $userPassword;
    /**
     * @var string
     */
    public $passwordRepeat;
    /**
     * @var string
     */
    public $captchaUser;
    /**
     * @var SiteUser
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'passwordRepeat', 'userPassword', 'captchaUser', 'role', 'age', 'class',
                'school', 'email'], 'required'],
            ['captchaUser', 'captcha', 'captchaAction' => '/profile/captcha'],
            ['userPassword', 'string', 'min' => 6],
            [['name', 'surname', 'role', 'age', 'class', 'school'], 'uniqueSiteUser'],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'userPassword'],
        ];
    }

    public function uniqueSiteUser($attribute, $params, $validator)
    {
        $userExists = SiteUser::findOne([
            'name' => $this->name,
            'surname' => $this->surname,
            'role' => $this->role,
            'age' => $this->age,
            'school' => $this->school,
            'email' => $this->email,
        ]);

        if ($userExists) {
            $this->addError($attribute, "Такий користувач вже існує ({$this->name} {$this->surname})");
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => "Ім'я",
            'surname' => 'Прізвище',
            'email' => 'Електронна пошта',
            'role' => 'Роль',
            'age' => 'Вік',
            'class' => 'Клас',
            'school' => 'Школа',
            'nickname' => 'Логін/Нік',
            'userPassword' => 'Пароль',
            'passwordRepeat' => 'Пароль ще раз',
            'captchaUser' => 'Капча',
        ];
    }

    /**
     * @return SiteUser
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function register()
    {
        $user = new SiteUser();
        $user->name = $this->name;
        $user->surname = $this->surname;
        $user->role = $this->role;
        $user->age = $this->age;
        $user->class = $this->class;
        $user->school = $this->school;
        $user->email = $this->email;
        $user->userPassword = $this->userPassword;
        $user->passwordRepeat = $this->passwordRepeat;

        $user->generateAuthKey();

        $this->_user = $user;

        if ($this->validate() && $user->validate()) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $user->save(false);

                $transaction->commit();
            } catch (\Throwable $e) {
                $transaction->rollBack();
            }

            return true;
        }

        $this->addErrors($this->_user->getErrors());

        return false;
    }
}