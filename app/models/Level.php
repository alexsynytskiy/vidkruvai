<?php

namespace app\models;

use app\components\AppMsg;
use app\components\behaviors\AwardBehavior;
use app\models\definitions\DefEntityAchievement;
use app\models\definitions\DefLevel;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class Level
 * @package app\models
 *
 * @property integer $id
 * @property string $entity_type
 * @property integer $group_id
 * @property integer $num
 * @property integer $required_experience
 * @property integer $base_level
 * @property string $created_at
 * @property string $archived
 *
 * @property Category $levelgroup
 * @property SiteUser[] $users
 * @property EntityLevelHistory[] $userLevelHistories
 * @property Award[] $awards
 * @property Level $nextLevel
 */
class Level extends ActiveRecord
{
    /**
     * Const of archive status of the records
     */
    const IS_ARCHIVED = 'yes';
    const IS_NOT_ARCHIVED = 'no';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'level';
    }

    /**
     * @return string
     */
    public static function junctionAwardTable()
    {
        return 'level_award';
    }

    /**
     * @return string
     */
    public static function junctionAwardAttribute()
    {
        return 'level_id';
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
            [['group_id', 'required_experience', 'num', 'entity_type'], 'required'],
            [['group_id', 'required_experience', 'base_level', 'num'], 'integer'],
            [['base_level'], 'validateBaseLevel'],
            [['awardIDs'], 'safe'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(),
                'targetAttribute' => ['group_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entity_type' => AppMsg::t('Получатель'),
            'group_id' => AppMsg::t('Группа'),
            'num' => AppMsg::t('№ Уровня'),
            'required_experience' => AppMsg::t('Требуемый опыт'),
            'base_level' => AppMsg::t('Базовый уровень'),
            'created_at' => AppMsg::t('Создано'),
            'archived' => AppMsg::t('Архивный'),
            'awardIDs' => AppMsg::t('Награды'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ((int)$this->base_level === DefLevel::BASE_LEVEL) {
                if ($insert) {
                    static::updateAll(['base_level' => 0]);
                } else {
                    static::updateAll(['base_level' => 0], ['!=', 'id', $this->id]);
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validateBaseLevel($attribute, $params)
    {
        $value = (int)$this->$attribute;

        if ($value !== DefLevel::BASE_LEVEL) {
            $query = static::find();

            if (!$this->isNewRecord) {
                $query->where(['!=', 'id', $this->id]);
            }

            $countBaseLevels = $query->andWhere(['base_level' => DefLevel::BASE_LEVEL])->count();

            if (!$countBaseLevels) {
                $this->addError($attribute, 'Для корректной работы в системе должен быть "Базовый" уровень.');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @return static
     * @throws \Exception
     */
    public static function getBaseLevel()
    {
        $level = static::findOne(['base_level' => DefLevel::BASE_LEVEL]);
        if (!$level) {
            throw new \Exception('Base level is not presents.');
        }

        return $level;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLevelgroup()
    {
        return $this->hasOne(Category::className(), ['id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(SiteUser::className(), ['level_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserLevelHistories()
    {
        return $this->hasMany(EntityLevelHistory::className(), ['level_id' => 'id'])
            ->andOnCondition(['entity_type' => DefEntityAchievement::ENTITY_USER]);
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
     * @param $startExp
     * @param $endExp
     * @param $entityType
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getPassedLevels($startExp, $endExp, $entityType)
    {
        return static::find()
            ->alias('l')
            ->where(['>', 'l.required_experience', $startExp])
            ->andWhere(['<=', 'l.required_experience', $endExp])
            ->andWhere(['entity_type' => $entityType])
            ->asArray()
            ->all();
    }

    /**
     * @param $currentLevel
     * @param $selectType
     * @param $entityType
     * @param int $levelsCount
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getLevels($currentLevel, $selectType, $entityType, $levelsCount = 2)
    {
        return static::find()
            ->alias('l')
            ->where([$selectType, 'l.num', $currentLevel])
            ->andWhere(['l.archived' => self::IS_NOT_ARCHIVED])
            ->andWhere(['entity_type' => $entityType])
            ->limit($levelsCount)
            ->all();
    }

    /**
     * @param $exp
     * @param $entityType
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getPrevLevelByExp($exp, $entityType)
    {
        return static::find()
            ->where(['<', 'required_experience', $exp])
            ->andWhere(['archived' => self::IS_NOT_ARCHIVED])
            ->orderBy('required_experience DESC')
            ->andWhere(['entity_type' => $entityType])
            ->asArray()
            ->one();
    }

    /**
     * @param $entityType
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getNextLevel($entityType)
    {
        return static::find()
            ->where(['>', 'required_experience', $this->required_experience])
            ->andWhere(['archived' => self::IS_NOT_ARCHIVED])
            ->andWhere(['entity_type' => $entityType])
            ->orderBy('required_experience ASC')
            ->one();
    }

    /**
     * @param $levelId
     * @param $entityType
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getListAwards($levelId, $entityType)
    {
        return ArrayHelper::getValue(
            static::find()
                ->with('awards')
                ->where(['id' => $levelId])
                ->andWhere(['entity_type' => $entityType])
                ->one(),
            'awards',
            []
        );
    }

    /**
     * @return string
     */
    public function getLandingAwardsString()
    {
        $stringAwards = '';
        /** @var Award $award */
        foreach ($this->awards as $award) {
            $stringAwards .= $award->name . ', ';
        }

        return rtrim($stringAwards, ', ');
    }
}
