<?php

namespace acp\models;

use Yii;
use acp\components\ActiveRecord;
use acp\components\AcpMsg;

/**
 * This is the model class for table "notification".
 *
 * @property string             $id
 * @property string             $category
 * @property string             $title
 * @property string             $message
 * @property string             $target_link
 * @property string             $type
 * @property string             $created_at
 *
 * @property NotificationUser[] $notificationUsers
 * @property User[]             $users
 */
class Notification extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'notification';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['category', 'message', 'type'], 'required'],
            [['category', 'message'], 'string'],
            [['created_at'], 'safe'],
            [['type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id'         => AcpMsg::t('ID'),
            'category'   => AcpMsg::t('Категория'),
            'message'    => AcpMsg::t('Сообщение'),
            'type'       => AcpMsg::t('Тип'),
            'created_at' => AcpMsg::t('Создано'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationUsers() {
        return $this->hasMany(NotificationUser::class, ['n_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers() {
        return $this->hasMany(User::class, ['id' => 'user_id'])->viaTable('notification_user', ['n_id' => 'id']);
    }
}
