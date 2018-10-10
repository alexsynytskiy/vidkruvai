<?php

namespace app\models;

use app\components\AppMsg;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "schooltypes".
 *
 * @property integer $id
 * @property string $name
 *
 */
class SchoolType extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schooltypes';
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
            'name' => AppMsg::t('Тип'),
        ];
    }

    /**
     * @return array
     */
    public static function getList()
    {
        $names = [];

        /** @var SchoolType[] $types */
        $types = self::find()->all();

        foreach ($types as $type) {
            $names[$type->id] = $type->name;
        }

        return $names;
    }
}
