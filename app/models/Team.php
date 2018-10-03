<?php

namespace app\models;

use app\components\AppMsg;
use app\models\definitions\DefTeamSiteUser;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\easyii\helpers\Mail;
use yii\easyii\models\Setting;
use yii\helpers\Url;

/**
 * Class TeamSiteUser
 * @property integer $id
 * @property string $name
 * @property string $avatar
 * @property string $status
 * @property integer $level_id
 * @property integer $level_experience
 * @property integer $total_experience
 * @property string $created_at
 * @property string $updated_at
 *
 * @property TeamSiteUser[] $teamUsers
 *
 * @package app\models
 */
class Team extends ActiveRecord
{
    public static function tableName()
    {
        return 'team';
    }

    public function rules()
    {
        return [
            [['level_id'], 'integer'],
            [['name', 'status'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => AppMsg::t('Назва команди'),
            'avatar' => AppMsg::t('Зображення'),
            'created_at' => AppMsg::t('Створено'),
            'updated_at' => AppMsg::t('Оновлено'),
            'status' => AppMsg::t('Статус'),
            'level_id' => AppMsg::t('ID Рівня'),
            'level_experience' => AppMsg::t('Досвід на поточному рівні'),
            'total_experience' => AppMsg::t('Загальний досвід'),
        ];
    }

    /**
     * @return bool
     */
    public function mailAdmin()
    {
        $captain = $this->teamCaptain();

        return Mail::send(
            Setting::get('admin_email'),
            AppMsg::t('Створено нову команду'),
            '@app/mail/uk/admin_team_created',
            [
                'captainName' => $captain->getFullName(),
                'link' => Url::to([
                    '/admin/team/a/view',
                    'id' => $this->primaryKey
                ], true),
            ]
        );
    }

    /**
     * @return array|null|SiteUser
     */
    public function teamCaptain() {
        return SiteUser::find()
            ->alias('su')
            ->innerJoin(TeamSiteUser::tableName() . ' tsu', 'tsu.site_user_id = su.id')
            ->where(['tsu.team_id' => $this->id, 'tsu.role' => DefTeamSiteUser::ROLE_CAPTAIN])
            ->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeamUsers()
    {
        return $this->hasMany(TeamSiteUser::className(), ['team_id' => 'id']);
    }
}
