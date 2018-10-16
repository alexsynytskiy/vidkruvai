<?php

namespace app\models;

use app\components\AppMsg;
use app\models\definitions\DefEntityAchievement;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "entity_achievement".
 *
 * @property integer $id
 * @property integer $achievement_id
 * @property integer $entity_id
 * @property string $entity_type
 * @property integer $performed_steps
 * @property integer $done
 * @property integer $is_first
 * @property string $created_at
 * @property string $done_at
 *
 * @property Achievement $achievement
 * @property SiteUser $user
 * @property Team $team
 */
class EntityAchievement extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'entity_achievement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['achievement_id', 'entity_id', 'entity_type'], 'required'],
            [['achievement_id', 'entity_id', 'performed_steps', 'done', 'is_first'], 'integer'],
            [['entity_type'], 'string'],
            [['created_at'], 'safe'],
            [['achievement_id'], 'exist', 'skipOnError' => true, 'targetClass' => Achievement::className(),
                'targetAttribute' => ['achievement_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'achievement_id' => AppMsg::t('Достижение'),
            'entity_id' => AppMsg::t('Получатель'),
            'entity_type' => AppMsg::t('Тип получателя'),
            'performed_steps' => AppMsg::t('Завершено шагов'),
            'is_first' => AppMsg::t('Первое достижение группы'),
            'done' => AppMsg::t('Завершено'),
            'done_at' => AppMsg::t('Дата завершения'),
            'created_at' => AppMsg::t('Создано'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAchievement()
    {
        return $this->hasOne(Achievement::className(), ['id' => 'achievement_id']);
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
     * @param string $achievementClass
     * @param int $entityId
     * @param string $entityType
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function checkEntityAchievement($achievementClass, $entityId, $entityType)
    {
        $achievementTable = Achievement::tableName();
        $userAchievementTable = self::tableName();

        return static::find()
            ->innerJoin($achievementTable, $achievementTable . '.`id` = ' . $userAchievementTable . '.`achievement_id`')
            ->where([
                $userAchievementTable . '.`entity_id`' => $entityId,
                $userAchievementTable . '.`entity_type`' => $entityType,
                $achievementTable . '.`class_name`' => $achievementClass,
                $achievementTable . '.`archived`' => Achievement::IS_NOT_ARCHIVED,
                $achievementTable . '.`entity_type`' => $entityType,
            ])
            ->one();
    }

    /**
     * @param string $achievementClass
     * @param int $achievementId
     * @param int $entityId
     * @param string $entityType
     *
     * @return EntityAchievement|array|null|ActiveRecord
     * @throws \Exception
     */
    public static function getEntityAchievementByID($achievementClass, $achievementId, $entityId, $entityType)
    {
        $achievementTable = Achievement::tableName();
        $userAchievementTable = self::tableName();

        $achievement = static::find()
            ->innerJoin($achievementTable, $achievementTable . '.`id` = ' .
                $userAchievementTable . '.`achievement_id`')
            ->where([
                $achievementTable . '.`id`' => $achievementId,
                $userAchievementTable . '.`entity_id`' => $entityId,
                $userAchievementTable . '.`entity_type`' => $entityType,
                $achievementTable . '.`archived`' => Achievement::IS_NOT_ARCHIVED,
            ])
            ->one();

        if (!$achievement) {
            $achievement = static::createEntityAchievement($achievementClass, $achievementId, $entityId, $entityType);
        }

        return $achievement;
    }

    /**
     * @param string $achievementClass
     * @param int $achievementId
     * @param int $entityId
     * @param string $entityType
     *
     * @return static
     * @throws \Exception
     */
    public static function createEntityAchievement($achievementClass, $achievementId, $entityId, $entityType)
    {
        if (!$achievementId) {
            throw new \Exception("Achievement {$achievementClass} is not found.");
        }

        $achievement = new static;
        $achievement->entity_id = $entityId;
        $achievement->entity_type = $entityType;
        $achievement->achievement_id = $achievementId;
        $achievement->save(false);

        $achievement->refresh();

        return $achievement;
    }

    /**
     * @param int $entityId
     * @param string $entityType
     *
     * @return int|string
     */
    public static function getPassedAchievements($entityId, $entityType)
    {
        return static::find()
            ->where(['entity_id' => $entityId, 'entity_type' => $entityType, 'done' => DefEntityAchievement::IS_DONE])
            ->count();
    }
}
