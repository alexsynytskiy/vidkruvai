<?php

namespace app\models;

use app\components\AppMsg;
use app\models\definitions\DefSiteUser;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "site_user".
 *
 * @property integer $id
 * @property string $email
 * @property string $name
 * @property string $surname
 * @property string $avatar
 * @property string $role
 * @property string $age
 * @property string $class
 * @property string $school
 * @property string $password
 * @property integer $login_count
 * @property integer $agreement_read
 * @property string $status
 * @property string $language
 * @property integer $level_id
 * @property integer $level_experience
 * @property integer $total_experience
 * @property integer $auth_key
 * @property string $created_at
 * @property string $updated_at
 *
 * @property IdentityInterface|null|SiteUser $identity The identity object associated with the currently logged-in
 * user. `null` is returned if the user is not logged in (not authenticated).
 * @property string $passwordWithSalt
 * @property Answer[] $answers
 * @property Level $level
 * @property Team $team
 *
 */
class SiteUser extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_UNCONFIRMED = 'UNCONFIRMED';
    const STATUS_BANNED = 'BANNED';
    const STATUS_DISABLED = 'DISABLED';

    const ROLE_MENTOR = 'mentor';
    const ROLE_PARTICIPANT = 'participant';

    /**
     * Salt uses to hash user ID
     */
    const HASH_ID_SALT = 'UfI6m8gqwriLDLvi9W5G';
    /**
     * Salt uses to hash user Password
     */
    const PASSWORD_SALT = 'aowherw34rywherfghweifhso';

    const AGREEMENT_READ = 1;
    /**
     * @var string
     */
    public $userPassword;
    /**
     * @var string
     */
    public $passwordRepeat;

    /**
     * @return string
     */
    public static function answersTableName()
    {
        return 'question_answer_user';
    }

    /**
     * @return string
     */
    public static function teamParticipationTableName()
    {
        return TeamSiteUser::tableName();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_user';
    }

    /**
     * @param bool $insert
     *
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->userPassword) {
                $this->password = \Yii::$app->security->generatePasswordHash($this->passwordWithSalt);
                $this->status = self::STATUS_UNCONFIRMED;
            }

            if ($insert) {
                $this->status = DefSiteUser::STATUS_ACTIVE;
            }

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'passwordRepeat', 'userPassword'], 'safe'],
            [['password'], 'string', 'min' => 4, 'max' => 60],
            [['email'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['userPassword'], 'string', 'min' => 4, 'max' => 60],
            [['name', 'class', 'school'], 'string', 'max' => 255],
            [['agreement_read', 'login_count', 'age'], 'integer'],
            [['name', 'surname'], 'unique', 'targetAttribute' => ['name', 'surname']],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'userPassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => AppMsg::t('Email'),
            'name' => AppMsg::t('Имя пользователя'),
            'password' => AppMsg::t('Пароль'),
            'created_at' => AppMsg::t('Создано'),
            'updated_at' => AppMsg::t('Обновлено'),
            'login_count' => AppMsg::t('Количество авторизаций'),
            'status' => AppMsg::t('Статус'),
            'language' => AppMsg::t('Язык'),
            'level_id' => AppMsg::t('ID Уровня'),
            'level_experience' => AppMsg::t('Опыта на уровне'),
            'total_experience' => AppMsg::t('Всего опыта'),
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @return array
     */
    public function getUserLevelInfo()
    {
        $result = [];

        $result['currentLevel'] = $this->level->num;
        $result['currentLevelExp'] = $this->level_experience;
        $result['currentLevelGroup'] = $this->level->levelgroup->name;
        $result['currentLevelGroupSlug'] = $this->level->levelgroup->slug;
        $result['currentLevelMin'] = $this->total_experience - $this->level_experience;

        if ($this->level->nextLevel) {
            $result['currentLevelMaxExp'] = $this->level->nextLevel->required_experience;
            $result['currentLevelMaxExpProfile'] = $this->level->nextLevel->required_experience -
                $this->level->required_experience;
            $result['currentLevelAward'] = $this->level->nextLevel->awards;
        } else {
            $result['currentLevelMaxExp'] = $this->total_experience;
            $result['currentLevelMaxExpProfile'] = $this->total_experience;
            $result['currentLevelExp'] = $this->total_experience;
            $result['currentLevelAward'] = [];
        }

        return $result;
    }

    /**
     * @param SiteUser $user
     *
     * @return array
     */
    public static function getUserCredentials($user = null)
    {
        if ($user instanceof self) {
            $result = [
                'userPhoto' => $user->avatar,
                'userName' => $user->name,
                'userLevel' => $user->level->levelgroup->name,
                'userLevelNum' => $user->level->num,
                'levelGroupSlug' => $user->level->levelgroup->slug,
                'id' => $user->id,
            ];
        } else {
            $result = [
                'name' => $user->username
            ];
        }

        return $result;
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public static function getUserRenderingInfo($id)
    {
        $userId = \Yii::$app->siteUser->id;
        $user = \Yii::$app->siteUser->identity;
        $preview = false;

        if ($id && $user->id !== $id) {
            $user = self::findIdentity($id);
            $userId = $id;

            if (!$user) {
                throw new NotFoundHttpException();
            }

            $preview = true;
        }

        $result['id'] = $userId;
        $result['user'] = $user;
        $result['preview'] = $preview;

        return $result;
    }

    /**
     * @return string
     */
    public function getPasswordWithSalt()
    {
        return $this->userPassword . self::PASSWORD_SALT;
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return void|IdentityInterface
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @return string
     */
    public function hashId()
    {
        return md5($this->id . self::HASH_ID_SALT);
    }

    /**
     * @param $hash
     *
     * @return bool
     */
    public function validHashId($hash)
    {
        return $this->hashId() === $hash;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password . self::PASSWORD_SALT, $this->password);
    }

    /**
     * @throws \yii\base\Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    /**
     * @return void
     */
    public function updateLoginCount()
    {
        $this->updateCounters(['login_count' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['id' => 'answer_id'])
            ->viaTable(static::answersTableName(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id'])
            ->viaTable(static::teamParticipationTableName(), ['site_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(Level::className(), ['id' => 'level_id']);
    }

    /**
     * @return array
     */
    public static function getRoles()
    {
        return [
            self::ROLE_PARTICIPANT => 'Учасник',
            self::ROLE_MENTOR => 'Ментор',
        ];
    }

    /**
     * @param int $userId
     * @param string $language
     *
     * @return int
     */
    public static function updateUserPreferredLanguage($userId, $language)
    {
        try {
            return \Yii::$app->db->createCommand('UPDATE ' . static::tableName() .
                ' SET language = :lang WHERE id = :userId', [':lang' => $language, ':userId' => $userId])->execute();
        } catch (\Exception $e) {
            return 0;
        }
    }
}
