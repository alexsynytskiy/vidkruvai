<?php
namespace yii\easyii\modules\customs\models;

use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;
use Yii;
use yii\easyii\components\helpers\LanguageHelper;
use yii\easyii\models\Photo;
use yii\helpers\StringHelper;

class Customs extends \yii\easyii\components\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    /**
     * @return MultilingualQuery
     */
    public static function find() {
        return new MultilingualQuery(get_called_class());
    }

    public static function tableName()
    {
        return 'easyii_customs';
    }

    public function rules()
    {
        return [
            [['latitude', 'longitude'], 'string'],
            [['title', 'short', 'text'], 'trim'],
            ['title', 'string', 'max' => 128],
            ['country', 'string', 'max' => 128],
            ['image', 'image'],
            [['status'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title'     => Yii::t('easyii', 'Title'),
            'text'      => Yii::t('easyii', 'Text'),
            'short'     => Yii::t('easyii/customs', 'Short'),
            'image'     => Yii::t('easyii', 'Image'),
            'latitude'  => Yii::t('easyii/customs', 'Latitude'),
            'longitude' => Yii::t('easyii/customs', 'Longitude'),
            'country'   => Yii::t('easyii/customs', 'Country'),
        ];
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'multilingual' => [
                'class'           => MultilingualBehavior::className(),
                'languages'       => LanguageHelper::getLanguages(),
                'defaultLanguage' => Yii::$app->language,
                'langForeignKey'  => 'customs_id',
                'tableName'       => 'customs_i18n',
                'attributes'      => [
                    'title', 'text', 'short'
                ],
            ],
        ]);
    }

    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['item_id' => 'customs_id'])->where(['class' => self::className()])->sort();
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $settings = Yii::$app->getModule('admin')->activeModules['customs']->settings;
            $this->short = StringHelper::truncate($settings['enableShort'] ? $this->short : strip_tags($this->text), $settings['shortMaxLength']);

            if(!$insert && $this->image != $this->oldAttributes['image'] && $this->oldAttributes['image']){
                @unlink(Yii::getAlias('@webroot').$this->oldAttributes['image']);
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        if($this->image){
            @unlink(Yii::getAlias('@webroot').$this->image);
        }

        foreach($this->getPhotos()->all() as $photo){
            $photo->delete();
        }
    }
}