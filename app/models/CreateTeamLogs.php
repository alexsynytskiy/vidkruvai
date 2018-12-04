<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Class CreateTeamLogs
 * @property integer $id
 * @property integer $site_user_id
 * @property string $type
 * @property string $data_old
 * @property string $data_new
 * @property string $created_at
 *
 * @property SiteUser $user
 *
 * @package app\models
 */
class CreateTeamLogs extends ActiveRecord
{
    public static function tableName()
    {
        return 'create_team_logs';
    }

    public function rules()
    {
        return [
            [['site_user_id'], 'integer'],
            [['type'], 'string', 'max' => 10],
            [['created_at'], 'safe'],
        ];
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

    /**
     * @param string $type
     * @param string $instanceNew
     * @param string|null $instanceOld
     */
    public static function saveLog($type, $instanceNew, $instanceOld = null)
    {
        $log = new self;
        $log->site_user_id = \Yii::$app->siteUser->id;
        $log->type = $type;
        $log->data_new = $instanceNew;

        if ($instanceOld) {
            $log->data_old = $instanceOld;
        }

        $log->save();
    }
}
