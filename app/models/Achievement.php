<?php

namespace app\models;

use app\components\AppMsg;
use app\components\behaviors\AwardBehavior;
use app\models\definitions\DefAchievements;
use app\models\definitions\DefEntityAchievement;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "achievement".
 *
 * @property integer $id
 * @property integer $entity_type
 * @property integer $group_id
 * @property string $name
 * @property string $class_name
 * @property integer $required_steps
 * @property integer $priority
 * @property string $description
 * @property string $archived
 * @property string $created_at
 *
 * @property EntityAchievement[] $userAchievements
 * @property Award[] $awards
 * @property Category $group
 */
class Achievement extends ActiveRecord
{
    /**
     * Const of archive status of the records
     */
    const IS_ARCHIVED = 'yes';
    const IS_NOT_ARCHIVED = 'no';
    /**
     * Const of publish status of the records
     */
    const IS_PUBLISH = 'yes';
    const IS_NOT_PUBLISH = 'no';
    /**
     * Const of publish status of the records
     */
    const IS_ACTIVE = 'yes';
    const IS_NOT_ACTIVE = 'no';

    /**
     * @var integer
     */
    public $userDone;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'achievement';
    }

    /**
     * @return string
     */
    public static function junctionAwardTable()
    {
        return 'achievement_award';
    }

    /**
     * @return string
     */
    public static function junctionAwardAttribute()
    {
        return 'achievement_id';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => AwardBehavior::className(),
                'junctionTable' => static::junctionAwardTable(),
                'entityAttribute' => static::junctionAwardAttribute(),
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'class_name', 'required_steps', 'priority', 'entity_type'], 'required'],
            [['group_id', 'priority'], 'integer'],
            [['required_steps'], 'integer', 'min' => 0],
            [['created_at', 'awardIDs', 'priority'], 'safe'],
            [['name', 'class_name', 'archived', 'entity_type'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1000],
            ['class_name', 'validateClassName'],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validateClassName($attribute, $params)
    {
        if (!class_exists(DefAchievements::NAMESPACE_RULES . $this->$attribute)) {
            $this->addError($attribute, 'Класса ' . $this->$attribute . ' не существует');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entity_type' => AppMsg::t('Получатель достижения'),
            'group_id' => AppMsg::t('Группа достижений'),
            'name' => AppMsg::t('Название'),
            'class_name' => AppMsg::t('Класс'),
            'archived' => AppMsg::t('Архивирован'),
            'created_at' => AppMsg::t('Создано'),
            'required_steps' => AppMsg::t('Необходимо шагов'),
            'priority' => AppMsg::t('Приоритет'),
            'description' => AppMsg::t('Описание'),
            'awardIDs' => AppMsg::t('Награды'),
        ];
    }

    /**
     * @param int $entityId
     * @param string $entityType
     * @return \yii\db\ActiveQuery
     */
    public function getEntityAchievementStatus($entityId, $entityType)
    {
        return $this->hasOne(EntityAchievement::className(), ['achievement_id' => 'id'])
            ->andOnCondition(['entity_type' => $entityType, 'entity_id' => $entityId]);
    }

    /**
     * @param string $entityType
     * @return \yii\db\ActiveQuery
     */
    public function getEntityAchievements($entityType)
    {
        return $this->hasMany(EntityAchievement::className(), ['achievement_id' => 'id'])
            ->andOnCondition(['entity_type' => $entityType]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAwards()
    {
        return $this->hasMany(Award::className(), ['id' => 'award_id'])
            ->viaTable(static::junctionAwardTable(), [static::junctionAwardAttribute() => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Category::className(), ['id' => 'group_id']);
    }

    /**
     * @param array $className
     * @param int $entityId
     * @param string $entityType
     *
     * @return array|ActiveRecord[]
     */
    public static function findIdentityByClasses($className, $entityId, $entityType)
    {
        return static::find()
            ->alias('a')
            ->select(['a.*', 'u.done as userDone'])
            ->leftJoin(EntityAchievement::tableName() . ' as u',
                'u.achievement_id = a.id AND u.entity_id = :entityId AND u.entity_type = :entityType',
                ['entityId' => $entityId, 'entityType' => $entityType])
            ->where(['a.class_name' => array_values($className)])
            ->all();
    }

    /**
     * @param int $entityId
     * @param string $entityType
     * @param string $className
     * @param string $type
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getAchievementToIncrease($entityId, $entityType, $className, $type = 'INNER JOIN')
    {
        return static::find()
            ->alias('a')
            ->select(['a.*'])
            ->join($type, EntityAchievement::tableName() . ' as u',
                'u.achievement_id = a.id AND u.entity_id = :entityId AND u.done != :done',
                ['entityId' => $entityId, 'done' => DefEntityAchievement::IS_DONE])
            ->where([
                'a.class_name' => $className,
                'a.archived' => static::IS_NOT_ARCHIVED,
                'a.entity_type' => $entityType,
            ])
            ->orderBy('a.required_steps ASC')
            ->one();
    }

    /**
     * @param int $entityId
     * @param string $entityType
     * @param $className
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getAchievementByClassNameAndEntity($entityId, $entityType, $className)
    {
        if ($result = self::getAchievementToIncrease($entityId, $entityType, $className)) {
            return $result;
        }

        return self::getAchievementToIncrease($entityId, $entityType, $className, 'LEFT JOIN');
    }

    /**
     * @param string $achievementClass
     * @param int $achievementId
     * @param string $entityType
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getAchievementsToUpdatePerformedSteps($achievementClass, $achievementId, $entityType)
    {
        return static::find()
            ->alias('a')
            ->where([
                'a.class_name' => $achievementClass,
                'a.archived' => static::IS_NOT_ARCHIVED,
                'entity_type' => $entityType,
            ])
            ->andWhere(['>', 'a.id', $achievementId])
            ->all();
    }

    /**
     * @param int $achievementId
     * @param string $entityType
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getListAwards($achievementId, $entityType)
    {
        return ArrayHelper::getValue(
            static::find()
                ->with('awards')
                ->where(['id' => $achievementId, 'entity_type' => $entityType])
                ->one(),
            'awards',
            []
        );
    }

    /**
     * @param int $entityId
     * @param string $entityType
     *
     * @return array
     */
    public static function getAchievementsInProgress($entityId, $entityType)
    {
        return static::find()
            ->alias('a')
            ->innerJoin(EntityAchievement::tableName() . ' u', 'u.achievement_id = a.id')
            ->where(['u.done' => 0, 'a.archived' => self::IS_NOT_ARCHIVED])
            ->andWhere(['u.entity_id' => $entityId, 'u.entity_type' => $entityType, 'a.entity_type' => $entityType])
            ->orderBy('a.priority DESC')
            ->limit(3)
            ->all();
    }

    /**
     * @param int $entityId
     * @param string $entityType
     * @param int $limit
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getAchievementsToStart($entityId, $entityType, $limit = 3)
    {
        $started = (new \yii\db\Query())
            ->from(EntityAchievement::tableName())
            ->select('achievement_id')
            ->andWhere(['entity_id' => $entityId, 'entity_type' => $entityType])
            ->all();

        $records = static::find()
            ->alias('a')
            ->where(['a.archived' => self::IS_NOT_ARCHIVED, 'a.entity_type' => $entityType])
            ->andWhere(['not in', 'a.id', ArrayHelper::getColumn($started, 'achievement_id')])
            ->orderBy('a.priority DESC')
            ->limit($limit)
            ->all();

        return $records;
    }

    /**
     * @param int $entityId
     * @param string $entityType
     * @param int $limit
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getAchievementsFinished($entityId, $entityType, $limit = 3)
    {
        return static::find()
            ->alias('a')
            ->innerJoin(EntityAchievement::tableName() . ' u', 'u.achievement_id = a.id')
            ->where(['u.done' => 1, 'a.archived' => self::IS_NOT_ARCHIVED, 'a.entity_type' => $entityType])
            ->andWhere(['u.entity_id' => $entityId, 'u.entity_type' => $entityType])
            ->orderBy('a.priority DESC')
            ->limit($limit)
            ->all();
    }

    /**
     * @return int|string
     */
    public static function getAchievementsCount()
    {
        return static::find()
            ->alias('a')
            ->where([
                'a.archived' => self::IS_NOT_ARCHIVED
            ])
            ->count();
    }
}
