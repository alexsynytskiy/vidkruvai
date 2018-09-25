<?php

namespace app\models;

use app\components\AppMsg;
use app\models\definitions\DefAward;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\validators\NumberValidator;

/**
 * This is the model class for table "award".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $type
 * @property integer $value
 * @property string $created_at
 * @property string $archived
 *
 * @property Achievement[] $achievementAwards
 * @property Level[] $levelAwards
 * @property UserAward[] $userAwards
 */
class Award extends ActiveRecord
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
        return 'award';
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if ($this->type === DefAward::TYPE_EXPERIENCE) {
                $numberValidator = new NumberValidator([
                    'attributes' => ['value'],
                    'integerOnly' => true,
                    'skipOnError' => true,
                    'min' => 0,
                    'max' => 1000000,
                    'tooBig' => AppMsg::t('Нельзя добавить опыта больше чем 1 000 000.'),
                ]);

                $this->validators[] = $numberValidator;
            }

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type', 'value'], 'required'],
            [['type'], 'string'],
            [['value'], 'integer', 'min' => 0, 'max' => 4294967295], //Limitations because UNSIGNED INT field in the DB
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => AppMsg::t('Название'),
            'description' => AppMsg::t('Описание'),
            'type' => AppMsg::t('Тип награды'),
            'value' => AppMsg::t('Значение'),
            'created_at' => AppMsg::t('Создано'),
            'archived' => AppMsg::t('Архивный'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAchievementAwards()
    {
        return $this->hasMany(Achievement::className(), ['id' => Achievement::junctionAwardAttribute()])
            ->viaTable(Achievement::junctionAwardTable(), ['award_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLevelAwards()
    {
        return $this->hasMany(Level::className(), ['id' => Level::junctionAwardAttribute()])
            ->viaTable(Level::junctionAwardTable(), ['award_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAwards()
    {
        return $this->hasMany(UserAward::className(), ['award_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getListAwards()
    {
        return ArrayHelper::map(
            static::find()
                ->where(['archived' => self::IS_NOT_ARCHIVED])
                ->orderBy('name')
                ->all(),
            'id',
            'name'
        );
    }
}
