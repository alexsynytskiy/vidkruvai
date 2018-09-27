<?php

namespace app\models;

use app\components\AppMsg;
use app\components\behaviors\AwardBehavior;
use app\models\definitions\DefAchievements;
use app\models\definitions\DefUserAchievement;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "achievement".
 *
 * @property integer $id
 * @property integer $group_id
 * @property string $name
 * @property string $class_name
 * @property integer $required_steps
 * @property integer $priority
 * @property string $description
 * @property string $archived
 * @property string $created_at
 *
 * @property UserAchievement[] $userAchievements
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
            [['name', 'class_name', 'required_steps', 'priority'], 'required'],
            [['group_id', 'priority'], 'integer'],
            [['required_steps'], 'integer', 'min' => 0],
            [['created_at', 'awardIDs', 'priority'], 'safe'],
            [['name', 'class_name', 'archived'], 'string', 'max' => 255],
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
     * @param $userId
     * @return \yii\db\ActiveQuery
     */
    public function getUserAchievementStatus($userId)
    {
        return $this->hasOne(UserAchievement::className(), ['achievement_id' => 'id'])
            ->andOnCondition(['site_user_id' => $userId]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAchievements()
    {
        return $this->hasMany(UserAchievement::className(), ['achievement_id' => 'id']);
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
     * @param integer $userId
     *
     * @return static[]
     */
    public static function findIdentityByClasses($className, $userId)
    {
        return static::find()
            ->alias('a')
            ->select(['a.*', 'u.done as userDone'])
            ->leftJoin(UserAchievement::tableName() . ' as u',
                'u.achievement_id = a.id AND u.site_user_id = :userId', ['userId' => $userId])
            ->where(['a.class_name' => array_values($className)])
            ->all();
    }

    /**
     * @param        $userId
     * @param        $className
     * @param string $type
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getAchievementToIncrease($userId, $className, $type = 'INNER JOIN')
    {
        return static::find()
            ->alias('a')
            ->select(['a.*'])
            ->join($type, UserAchievement::tableName() . ' as u',
                'u.achievement_id = a.id AND u.site_user_id = :userId AND u.done != :done',
                ['userId' => $userId, 'done' => DefUserAchievement::IS_DONE])
            ->where([
                'a.class_name' => $className,
                'a.archived' => static::IS_NOT_ARCHIVED,
            ])
            ->orderBy('a.required_steps ASC')
            ->one();
    }

    /**
     * @param $userId
     * @param $className
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getAchievementByClassNameAndUser($userId, $className)
    {
        if ($result = self::getAchievementToIncrease($userId, $className)) {
            return $result;
        }

        return self::getAchievementToIncrease($userId, $className, 'LEFT JOIN');
    }

    /**
     * @param $achievementClass
     * @param $achievementId
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getAchievementsToUpdatePerformedSteps($achievementClass, $achievementId)
    {
        return static::find()
            ->alias('a')
            ->where([
                'a.class_name' => $achievementClass,
                'a.archived' => static::IS_NOT_ARCHIVED
            ])
            ->andWhere(['>', 'a.id', $achievementId])
            ->all();
    }

    /**
     * @param $achievementId
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getListAwards($achievementId)
    {
        return ArrayHelper::getValue(
            static::find()
                ->with('awards')
                ->where(['id' => $achievementId])
                ->one(),
            'awards',
            []
        );
    }

    /**
     * @param $userId
     *
     * @return array
     */
    public static function getAchievementsInProgress($userId)
    {
        return static::find()
            ->alias('a')
            ->innerJoin(UserAchievement::tableName() . ' u', 'u.achievement_id = a.id')
            ->where(['u.done' => 0, 'a.archived' => self::IS_NOT_ARCHIVED])
            ->andWhere(['u.site_user_id' => $userId])
            ->orderBy('a.priority DESC')
            ->limit(3)
            ->all();
    }

    /**
     * @param int $userId
     * @param int $limit
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getAchievementsToStart($userId, $limit = 3)
    {
        $started = (new \yii\db\Query())
            ->from(UserAchievement::tableName())
            ->select('achievement_id')
            ->where(['site_user_id' => $userId])
            ->all();

        $records = static::find()
            ->alias('a')
            ->where(['a.archived' => self::IS_NOT_ARCHIVED])
            ->andWhere(['not in', 'a.id', ArrayHelper::getColumn($started, 'achievement_id')])
            ->orderBy('a.priority DESC')
            ->limit($limit)
            ->all();

        return $records;
    }

    /**
     * @param int $userId
     * @param int $limit
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getAchievementsFinished($userId, $limit = 3)
    {
        return static::find()
            ->alias('a')
            ->innerJoin(UserAchievement::tableName() . ' u', 'u.achievement_id = a.id')
            ->where(['u.site_user_id' => $userId, 'u.done' => 1, 'a.archived' => self::IS_NOT_ARCHIVED])
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
