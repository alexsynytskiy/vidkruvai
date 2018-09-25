<?php

namespace app\models;

use app\components\AppMsg;
use app\models\definitions\DefLevel;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class Level
 * @package app\models
 *
 * @property integer $id
 * @property integer $group_id
 * @property integer $num
 * @property integer $required_experience
 * @property integer $base_level
 * @property string $created_at
 * @property string $archived
 *
 * @property Category $levelgroup
 * @property SiteUser[] $users
 * @property UserLevelHistory[] $userLevelHistories
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
                'class' => AwardBehavior::class,
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
            [['group_id', 'required_experience', 'num'], 'required'],
            [['group_id', 'required_experience', 'base_level', 'num'], 'integer'],
            [['base_level'], 'validateBaseLevel'],
            [['awardIDs'], 'safe'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['group_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
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

            $countBaseLevels = $query->andwhere(['base_level' => DefLevel::BASE_LEVEL])
                ->count();

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
        return $this->hasMany(UserLevelHistory::className(), ['level_id' => 'id']);
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
     * @param     $startExp
     * @param     $endExp
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getPassedLevels($startExp, $endExp)
    {
        return static::find()
            ->alias('l')
            ->where(['>', 'l.required_experience', $startExp])
            ->andWhere(['<=', 'l.required_experience', $endExp])
            ->asArray()
            ->all();
    }

    /**
     * @param     $currentLevel
     * @param     $selectType
     * @param int $levelsCount
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getLevels($currentLevel, $selectType, $levelsCount = 2)
    {
        return static::find()
            ->alias('l')
            ->where([$selectType, 'l.num', $currentLevel])
            ->andWhere(['l.archived' => parent::IS_NOT_ARCHIVED])
            ->limit($levelsCount)
            ->all();
    }

    /**
     * @param $exp
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getPrevLevelByExp($exp)
    {
        return static::find()
            ->where(['<', 'required_experience', $exp])
            ->andWhere(['archived' => parent::IS_NOT_ARCHIVED])
            ->orderBy('required_experience DESC')
            ->asArray()
            ->one();
    }

    /**
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getNextLevel()
    {
        return static::find()
            ->where(['>', 'required_experience', $this->required_experience])
            ->andWhere(['archived' => parent::IS_NOT_ARCHIVED])
            ->orderBy('required_experience ASC')
            ->one();
    }

    /**
     * @param $levelId
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getListAwards($levelId)
    {
        return ArrayHelper::getValue(
            static::find()
                ->with('awards')
                ->where(['id' => $levelId])
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
