<?php
namespace yii\easyii\modules\lineup\models;

use kartik\daterange\DateRangeBehavior;
use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\easyii\behaviors\SeoBehavior;
use yii\easyii\behaviors\Taggable;
use yii\easyii\components\helpers\LanguageHelper;
use yii\easyii\models\Photo;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;

class Item extends \yii\easyii\components\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    public $oldRecord;

    /**
     * @return MultilingualQuery
     */
    public static function find() {
        return new MultilingualQuery(get_called_class());
    }

    public static function tableName()
    {
        return 'easyii_lineup_items';
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'short', 'text'], 'trim'],
            ['title', 'string', 'max' => 128],
            [['fb_link', 'tw_link', 'youtube_link', 'instagram_link', 'soundcloud_link'], 'string', 'max' => 200],
            [['fb_link', 'tw_link', 'youtube_link', 'instagram_link', 'soundcloud_link'], 'url'],
            ['image', 'image'],
            [['category_id', 'views', 'time', 'status', 'position', 'is_set'], 'integer'],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            ['status', 'default', 'value' => self::STATUS_ON],
            ['is_set', 'default', 'value' => self::STATUS_ON],
            ['position', 'default', 'value' => 200],
            [['date'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
            ['tagNames', 'safe']
        ];
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'title' => Yii::t('easyii', 'Title'),
            'text' => Yii::t('easyii', 'Text'),
            'short' => Yii::t('easyii', 'Short'),
            'image' => Yii::t('easyii', 'Image'),
            'time' => Yii::t('easyii', 'Time'),
            'date' => Yii::t('easyii', 'Time interval'),
            'slug' => Yii::t('easyii', 'Slug'),
            'tagNames' => Yii::t('easyii', 'Tags'),
            'fb_link'=> Yii::t('easyii', 'Facebook link'),
            'tw_link'=> Yii::t('easyii', 'Twitter link'),
            'youtube_link'=> Yii::t('easyii', 'Youtube link'),
            'instagram_link'=> Yii::t('easyii', 'Instagram link'),
            'soundcloud_link' => Yii::t('easyii', 'Soundcloud link'),
            'position' => Yii::t('easyii', 'Position'),
            'is_set' => Yii::t('easyii', 'Is artist announced'),
        ]);
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'multilingual' => [
                'class'           => MultilingualBehavior::className(),
                'languages'       => LanguageHelper::getLanguages(),
                'defaultLanguage' => Yii::$app->language,
                'langForeignKey'  => 'item_id',
                'tableName'       => 'items_i18n',
                'attributes'      => [
                    'title', 'text', 'short'
                ],
            ],
            'seoBehavior' => SeoBehavior::className(),
            'taggabble' => Taggable::className(),
            'sluggable' => [
                'class'        => SluggableBehavior::className(),
                'attribute'    => 'title',
                'ensureUnique' => true
            ],
            [
                'class' => DateRangeBehavior::className(),
                'attribute' => 'date',
                'dateStartAttribute' => 'start_time',
                'dateEndAttribute' => 'end_time',
            ]
        ]);
    }

    public function afterFind()
    {
        $this->oldRecord=clone $this;
        return parent::afterFind();
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }

    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['item_id' => 'item_id'])->where(['class' => self::className()])->sort();
    }

    public function beforeSave($insert)
    {
        if((!isset($this->oldRecord->position)) || (int)$this->position != $this->oldRecord->position) {
            $artists = static ::find()->all();

            foreach ($artists as $artist) {
                if($artist->position >= (int)$this->position) {
                    $artist->updateAttributes([
                        'position' => $artist->position + 1,
                    ]);
                }
            }
        }

        if (parent::beforeSave($insert)) {
            $settings = Yii::$app->getModule('admin')->activeModules['lineup']->settings;
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