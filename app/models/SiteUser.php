<?php

namespace app\models;

use app\components\AppMsg;
use app\models\definitions\DefEntityAchievement;
use app\models\definitions\DefSiteUser;
use app\models\definitions\DefTeamSiteUser;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;

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
 * @property integer $school_id
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
 * @property School $school
 * @property TeamSiteUser $teamParticipator
 */
class SiteUser extends ActiveRecord implements IdentityInterface
{
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
            [['name', 'class'], 'string', 'max' => 255],
            [['agreement_read', 'login_count', 'age', 'school_id'], 'integer'],
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
            'role' => AppMsg::t('Роль'),
            'password' => AppMsg::t('Пароль'),
            'created_at' => AppMsg::t('Создано'),
            'updated_at' => AppMsg::t('Обновлено'),
            'login_count' => AppMsg::t('Количество авторизаций'),
            'status' => AppMsg::t('Статус'),
            'school_id' => AppMsg::t('Школа'),
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
        return static::findOne(['email' => $email, 'status' => [DefSiteUser::STATUS_ACTIVE, DefSiteUser::STATUS_INACTIVE]]);
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

    public function getFullName()
    {
        return $this->name . ' ' . $this->surname;
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
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id'])
            ->viaTable(static::teamParticipationTableName(), ['site_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchool()
    {
        return $this->hasOne(School::className(), ['id' => 'school_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeamParticipator()
    {
        return $this->hasOne(TeamSiteUser::className(), ['site_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(Level::className(), ['id' => 'level_id'])
            ->andOnCondition(['entity_type' => DefEntityAchievement::ENTITY_USER]);
    }

    /**
     * @return bool
     */
    public function isCaptain()
    {
        return self::find()
            ->alias('su')
            ->innerJoin(TeamSiteUser::tableName() . ' tsu', 'tsu.site_user_id = su.id')
            ->innerJoin(Team::tableName() . ' t', 'tsu.team_id = t.id')
            ->where([
                'tsu.role' => DefTeamSiteUser::ROLE_CAPTAIN,
                'tsu.site_user_id' => $this->id,
            ])
            ->exists();
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
