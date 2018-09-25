<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class CategoryEntity
 * @package app\models
 *
 * @property integer $category_id
 * @property integer $entity_id
 * @property string $entity_type
 *
 * @property Category $category
 */
class CategoryEntity extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category_entity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'entity_id', 'entity_type'], 'required'],
            [['category_id', 'entity_id'], 'integer'],
            [['entity_type'], 'string', 'max' => 30],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(),
                'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'entity_id' => 'Entity ID',
            'entity_type' => 'Entity Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}
