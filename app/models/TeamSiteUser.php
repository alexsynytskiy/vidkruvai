<?php

namespace app\models;

use app\components\AppMsg;
use app\models\definitions\DefNotification;
use app\models\definitions\DefTeamSiteUser;
use yii\base\Security;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\easyii\helpers\Mail;
use yii\helpers\Url;

/**
 * Class TeamSiteUser
 * @property integer $id
 * @property integer $site_user_id
 * @property integer $team_id
 * @property string $email
 * @property string $role
 * @property string $hash
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Team $team
 * @property SiteUser $user
 *
 * @package app\models
 */
class TeamSiteUser extends ActiveRecord
{
    public static function tableName()
    {
        return 'team_site_user';
    }

    public function rules()
    {
        return [
            [['site_user_id', 'team_id'], 'integer'],
            [['email', 'status', 'role'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->hash = (new Security)->generateRandomString();
            }

            return true;
        }

        return false;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert && $this->role !== DefTeamSiteUser::ROLE_CAPTAIN) {
            $this->mailInvitedUser();
        }
    }

    /**
     * @return array
     */
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
     * @return bool
     */
    public function mailInvitedUser()
    {
        $team = $this->team;

        $unsubscribeLink = Url::to('/', true) .
            'decline/' . DefTeamSiteUser::RESPONSE_REMOVED . '/' . $this->hash;
        $registrationLink = Url::to('/register', true) . '/' . $this->hash;
        $teamName = $team->name;
        $teamLead = $team->teamCaptain() ? $team->teamCaptain()->getFullName() : '';
        $siteLink = Url::to('/', true);
        $notParticipantLink = Url::to('/', true) .
            'decline/' . DefTeamSiteUser::RESPONSE_DECLINED . '/' . $this->hash;

        $user = SiteUser::findOne(['email' => $this->email]);
        if ($user) {
            \Yii::$app->notification->addToUser($user, DefNotification::CATEGORY_TEAM,
                DefNotification::TYPE_TEAM_INVITATION, $registrationLink,
                ['team_captain' => $teamLead,
                    'team_name' => $teamName,
                    'created_at' => date('d-M-Y H:i:s')]);
        }

        return Mail::send(
            $this->email,
            AppMsg::t('Запрошення у команду'),
            '@app/mail/uk/invitation',
            [
                'unsubscribeLink' => $unsubscribeLink,
                'registrationLink' => $registrationLink,
                'teamName' => $teamName,
                'teamLead' => $teamLead,
                'siteLink' => $siteLink,
                'notParticipantLink' => $notParticipantLink,
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(SiteUser::className(), ['id' => 'site_user_id']);
    }
}
