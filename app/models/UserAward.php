<?php

namespace app\models;

use app\components\AppMsg;
use app\models\definitions\DefUserAward;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_award".
 *
 * @property integer $id
 * @property integer $site_user_id
 * @property integer $award_id
 * @property string $type
 * @property integer $object_id
 * @property string $created_at
 *
 * @property Award $award
 * @property SiteUser $user
 * @property Level $level
 * @property Achievement $achievement
 * @property string $objectName
 */
class UserAward extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_award';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['site_user_id', 'award_id', 'object_id'], 'required'],
            [['site_user_id', 'award_id', 'object_id'], 'integer'],
            [['type'], 'string'],
            [['created_at'], 'safe'],
            [['award_id'], 'exist', 'skipOnError' => true, 'targetClass' => Award::className(),
                'targetAttribute' => ['award_id' => 'id']],
            [['site_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => SiteUser::className(),
                'targetAttribute' => ['site_user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'site_user_id' => AppMsg::t('Пользователь'),
            'award_id' => AppMsg::t('Награда'),
            'type' => AppMsg::t('Тип награды'),
            'object_id' => AppMsg::t('Объект'),
            'transaction_id' => AppMsg::t('Транзакция'),
            'created_at' => AppMsg::t('Создано'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAward()
    {
        return $this->hasOne(Award::className(), ['id' => 'award_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(SiteUser::className(), ['id' => 'site_user_id']);
    }

    /**
     * @return null|\yii\db\ActiveQuery
     */
    public function getLevel()
    {
        if ($this->type === DefUserAward::TYPE_LEVEL) {
            return $this->hasOne(Level::className(), ['id' => 'object_id']);
        }

        return null;
    }

    /**
     * @return null|\yii\db\ActiveQuery
     */
    public function getAchievement()
    {
        if ($this->type === DefUserAward::TYPE_ACHIEVEMENT) {
            return $this->hasOne(Achievement::className(), ['id' => 'object_id']);
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getObjectName()
    {
        if ($this->type === DefUserAward::TYPE_ACHIEVEMENT) {
            return $this->achievement->name ?: null;
        }

        if ($this->type === DefUserAward::TYPE_LEVEL) {
            return isset($this->level->num) ? 'Уровень ' . $this->level->num : null;
        }

        return null;
    }
}
