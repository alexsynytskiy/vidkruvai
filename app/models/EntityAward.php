<?php

namespace app\models;

use app\components\AppMsg;
use app\models\definitions\DefEntityAchievement;
use app\models\definitions\DefEntityAward;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "entity_award".
 *
 * @property integer $id
 * @property integer $entity_id
 * @property string $entity_type
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
class EntityAward extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'entity_award';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_id', 'entity_type', 'award_id', 'object_id'], 'required'],
            [['entity_id', 'award_id', 'object_id'], 'integer'],
            [['type', 'entity_type'], 'string'],
            [['created_at'], 'safe'],
            [['award_id'], 'exist', 'skipOnError' => true, 'targetClass' => Award::className(),
                'targetAttribute' => ['award_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entity_id' => AppMsg::t('Получатель'),
            'entity_type' => AppMsg::t('Тип получателя'),
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
        return $this->hasOne(SiteUser::className(), ['id' => 'entity_id'])
            ->andOnCondition(['entity_type' => DefEntityAchievement::ENTITY_USER]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'entity_id'])
            ->andOnCondition(['entity_type' => DefEntityAchievement::ENTITY_TEAM]);
    }

    /**
     * @return null|\yii\db\ActiveQuery
     */
    public function getLevel()
    {
        if ($this->type === DefEntityAward::TYPE_LEVEL) {
            return $this->hasOne(Level::className(), ['id' => 'object_id']);
        }

        return null;
    }

    /**
     * @return null|\yii\db\ActiveQuery
     */
    public function getAchievement()
    {
        if ($this->type === DefEntityAward::TYPE_ACHIEVEMENT) {
            return $this->hasOne(Achievement::className(), ['id' => 'object_id']);
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getObjectName()
    {
        if ($this->type === DefEntityAward::TYPE_ACHIEVEMENT) {
            return $this->achievement->name ?: null;
        }

        if ($this->type === DefEntityAward::TYPE_LEVEL) {
            return isset($this->level->num) ? 'Уровень ' . $this->level->num : null;
        }

        return null;
    }
}
