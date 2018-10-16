<?php

namespace app\models;

use app\components\AppMsg;
use app\models\definitions\DefEntityAchievement;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * Class EntityLevelHistory
 * @package app\models
 *
 * @property integer $id
 * @property integer $entity_id
 * @property string $entity_type
 * @property integer $level_id
 * @property string $created_at
 *
 * @property SiteUser $user
 * @property Team $team
 * @property Level $level
 */
class EntityLevelHistory extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'entity_level_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_id', 'level_id', 'entity_type'], 'required'],
            [['entity_id', 'level_id'], 'integer'],
            [['created_at'], 'safe'],
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
            'entity_id' => AppMsg::t('Получатель'),
            'entity_type' => AppMsg::t('Тип получателя'),
            'level_id' => AppMsg::t('Уровень'),
            'created_at' => AppMsg::t('Создано'),
        ];
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
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(Level::className(), ['id' => 'level_id']);
    }

    /**
     * @param int $entityId
     * @param string $entityType
     * @param array $level
     * @throws \yii\db\Exception
     */
    public static function logHistory($entityId, $entityType, array $level)
    {
        \Yii::$app->db->createCommand()
            ->insert(static::tableName(), [
                'entity_id' => $entityId,
                'entity_type' => $entityType,
                'experience' => abs($level['addedExp']),
                'level_id' => $level['levelId'],
            ])
            ->execute();
    }

    /**
     * @param int $entityId
     * @param string $entityType
     * @param int $levelId
     *
     * @return bool
     */
    public static function isLevelUnlocked($entityId, $entityType, $levelId)
    {
        return (new Query)
            ->from(static::tableName())
            ->where([
                'entity_id' => $entityId,
                'entity_type' => $entityType,
                'level_id' => $levelId,
            ])
            ->exists();
    }
}
