<?php
namespace yii\easyii\models;

use Yii;
use yii\easyii\behaviors\SortableModel;
use yii\easyii\components\ActiveRecord;

/**
 * Class Photo
 * @package yii\easyii\models
 *
 * @property integer $item_id
 * @property string  $class
 * @property string  $image
 * @property string  $link
 * @property string  $description
 *
 */
class Photo extends ActiveRecord
{
    const PHOTO_MAX_WIDTH    = 1900;
    const PHOTO_THUMB_WIDTH  = 120;
    const PHOTO_THUMB_HEIGHT = 90;

    public static function tableName()
    {
        return 'easyii_photos';
    }

    public function rules()
    {
        return [
            [['class', 'item_id'], 'required'],
            ['item_id', 'integer'],
            ['image', 'image'],
            ['link', 'string'],
            ['description', 'trim']
        ];
    }

    public function behaviors()
    {
        return [
            SortableModel::className()
        ];
    }

    public function afterDelete()
    {
        parent::afterDelete();

        @unlink(Yii::getAlias('@webroot').$this->image);
    }
}