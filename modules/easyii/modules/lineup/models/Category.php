<?php
namespace yii\easyii\modules\lineup\models;

use creocoder\nestedsets\NestedSetsBehavior;
use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;
use yii\behaviors\SluggableBehavior;
use yii\easyii\behaviors\CacheFlush;
use yii\easyii\behaviors\SeoBehavior;
use yii\easyii\components\helpers\LanguageHelper;

class Category extends \yii\easyii\components\CategoryModel
{
    public function rules()
    {
        return [
            [['color', 'title', 'slug'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'color' => \Yii::t('easyii', 'Color'),
        ]);
    }

    public static function tableName()
    {
        return 'easyii_lineup_categories';
    }

    public function getItemsShedule()
    {
        return $this->hasMany(Item::className(), ['category_id' => 'category_id'])->where(['status' => parent::STATUS_ON])->orderBy('start_time ASC');
    }

    public function getItems()
    {
        return $this->hasMany(Item::className(), ['category_id' => 'category_id'])->orderBy('start_time ASC');
    }

    public function getItemsEnabled()
    {
        return $this->hasMany(Item::className(), ['category_id' => 'category_id'])->where(['status' => parent::STATUS_ON, 'is_set' => parent::STATUS_ON])->orderBy('start_time ASC');
    }

    public function afterDelete()
    {
        parent::afterDelete();

        foreach ($this->getItems()->all() as $item) {
            $item->delete();
        }
    }

    public static function getFestivalDays() {
        return static::find()->where(['depth' => 0])->orderBy('order_num DESC')->all();
    }
}