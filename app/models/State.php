<?php

namespace app\models;

use app\components\AppMsg;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "state".
 *
 * @property integer $id
 * @property string $name
 * @property string $map_code
 *
 * @property City[] $cities
 */
class State extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'state';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['name', 'map_code'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => AppMsg::t('Назва'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::className(), ['state_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getList()
    {
        $names = [];

        /** @var State[] $states */
        $states = self::find()->all();

        foreach ($states as $state) {
            $names[$state->id] = $state->name . ' обл.';
        }

        return $names;
    }
}
