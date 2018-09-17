<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\easyii\components\ActiveRecord;

/**
 * Class TeamSiteUser
 * @property integer $id
 * @property integer $site_user_id
 * @property integer $team_id
 * @property string $email
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SiteUser $user
 *
 * @package app\models
 */
class TeamSiteUser extends ActiveRecord
{
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_UNCONFIRMED = 'UNCONFIRMED';

    public static function tableName()
    {
        return 'team_site_user';
    }

    public function rules()
    {
        return [
            [['site_user_id', 'team_id'], 'integer'],
            [['email', 'status'], 'string', 'max' => 255],
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
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(SiteUser::className(), ['id' => 'site_user_id']);
    }
}
