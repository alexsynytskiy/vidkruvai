<?php

namespace app\models;

use app\components\AppMsg;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "school".
 *
 * @property integer $id
 * @property integer $city_id
 * @property integer $type_id
 * @property string $number
 * @property string $name
 *
 * @property City $city
 * @property SchoolType $type
 */
class School extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'school';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id', 'type_id'], 'required'],
            [['city_id', 'type_id', 'id'], 'integer'],
            [['number', 'name'], 'string'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(),
                'targetAttribute' => ['city_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolType::className(),
                'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_id' => AppMsg::t('City ID'),
            'type_id' => AppMsg::t('Type ID'),
            'number' => AppMsg::t('Номер'),
            'name' => AppMsg::t('Назва'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(SchoolType::className(), ['id' => 'type_id']);
    }

    /**
     * @return array
     */
    public static function getList()
    {
        $names = [];

        /** @var School[] $schools */
        $schools = self::find()->all();

        foreach ($schools as $school) {
            if ($school->city) {
                $names[$school->id] = $school->getFullName();
            }
        }

        return $names;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->city->state->name . ' обл, м. ' . $this->city->city . ' ' .
            ($this->type ? $this->type->name : '') . ' ' .
            ((int)$this->number > 0 || (int)$this->number <= 1000 ? '№' . $this->number : '') . ' ' .
            $this->name;
    }
}
