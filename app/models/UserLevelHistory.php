<?php

namespace app\models;

use app\components\AppMsg;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * Class UserLevelHistory
 * @package app\models
 *
 * @property integer $id
 * @property integer $site_user_id
 * @property integer $level_id
 * @property string $created_at
 *
 * @property SiteUser $user
 * @property Level $level
 */
class UserLevelHistory extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_level_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['site_user_id', 'level_id'], 'required'],
            [['site_user_id', 'level_id'], 'integer'],
            [['created_at'], 'safe'],
            [['site_user_id'], 'exist', 'skipOnError' => true,
                'targetClass' => SiteUser::className(), 'targetAttribute' => ['site_user_id' => 'id']],
            [['level_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Level::className(), 'targetAttribute' => ['level_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'site_user_id' => AppMsg::t('ID Пользователь'),
            'level_id' => AppMsg::t('Уровень'),
            'created_at' => AppMsg::t('Создано'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLandingUser()
    {
        return $this->hasOne(SiteUser::className(), ['id' => 'site_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(Level::className(), ['id' => 'level_id']);
    }

    /**
     * @param int $userId
     * @param array $level
     * @throws \yii\db\Exception
     */
    public static function logHistory($userId, array $level)
    {
        \Yii::$app->db->createCommand()
            ->insert(static::tableName(), [
                'site_user_id' => $userId,
                'experience' => abs($level['addedExp']),
                'level_id' => $level['levelId'],
            ])
            ->execute();
    }

    /**
     * @param int $userId
     * @param int $levelId
     *
     * @return bool
     */
    public static function isLevelUnlocked($userId, $levelId)
    {
        return (new Query)
            ->from(static::tableName())
            ->where(['site_user_id' => $userId, 'level_id' => $levelId])
            ->exists();
    }
}
