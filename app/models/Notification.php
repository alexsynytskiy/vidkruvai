<?php

namespace app\models;

use app\components\AppMsg;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "notification".
 *
 * @property string $id
 * @property string $category
 * @property string $title
 * @property string $message
 * @property string $target_link
 * @property string $type
 * @property string $created_at
 * @property string $updated_at
 *
 * @property NotificationUser[] $notificationUsers
 * @property SiteUser[] $users
 */
class Notification extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'message', 'type'], 'required'],
            [['category', 'message'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['type'], 'string', 'max' => 50],
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
            'id' => AppMsg::t('ID'),
            'category' => AppMsg::t('Категория'),
            'message' => AppMsg::t('Сообщение'),
            'type' => AppMsg::t('Тип'),
            'created_at' => AppMsg::t('Создано'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationUsers()
    {
        return $this->hasMany(NotificationUser::className(), ['n_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(SiteUser::className(), ['id' => 'user_id'])
            ->viaTable('notification_user', ['n_id' => 'id']);
    }
}
