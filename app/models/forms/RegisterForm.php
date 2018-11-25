<?php

namespace app\models\forms;

use app\models\definitions\DefSiteUser;
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
     * @var int
     */
    public $school_id;
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
            [['name', 'surname', 'passwordRepeat', 'userPassword', 'captchaUser', 'role', 'age',
                'school_id', 'email'], 'required'],
            ['captchaUser', 'captcha', 'captchaAction' => '/validation/captcha'],
            ['userPassword', 'string', 'min' => 6],
            [['role', 'class'], 'userClass'],
            [['email'], 'uniqueEmail'],
            [['name', 'surname', 'role', 'age', 'class', 'school_id'], 'uniqueSiteUser'],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'userPassword'],
        ];
    }

    public function userClass($attribute, $params, $validator)
    {
        if($this->role === DefSiteUser::ROLE_PARTICIPANT && (!$this->class || $this->class === '')) {
            $this->addError($attribute, "Учасник обов'язково має вказати клас");
        }
    }

    public function uniqueEmail($attribute, $params, $validator)
    {
        $userExists = SiteUser::find()->where([
            'email' => $this->email,
        ])->exists();

        if ($userExists) {
            $this->addError($attribute, "Такий e-mail вже існує ({$this->email})");
        }
    }

    public function uniqueSiteUser($attribute, $params, $validator)
    {
        $userExists = SiteUser::findOne([
            'name' => $this->name,
            'surname' => $this->surname,
            'role' => $this->role,
            'age' => $this->age,
            'school_id' => $this->school_id,
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
            'school_id' => 'Школа',
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
        $user->school_id = $this->school_id;
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