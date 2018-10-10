<?php

namespace app\models;

use app\components\AppMsg;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "state".
 *
 * @property integer $id
 * @property string $name
 *
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
            [['name'], 'string'],
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
